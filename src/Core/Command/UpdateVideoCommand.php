<?php

namespace App\Core\Command;

use App\Core\Entity\Playlist;
use App\Core\Entity\Video;
use App\Core\Service\YoutubeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class UpdateVideoCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('video:update')
            ->setDescription('Update video');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws InvalidArgumentException
     * @throws ServiceNotFoundException
     * @throws ServiceCircularReferenceException
     * @throws CommandNotFoundException
     * @throws \RuntimeException
     * @throws \Exception
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $youtubeService = $this->container->get(YoutubeService::class);

        /** @var Playlist[] $sessions */
        $playlists = $em
            ->getRepository(Playlist::class)
            ->findAll();
        $videoRepository = $em->getRepository(Video::class);

        foreach ($playlists as $playlist) {
            /** @var Playlist $playlist */
            $videos = $youtubeService->getPlaylistVideo($playlist->getYoutubeId());

            foreach ($videos as $videoYoutube) {
                /** @var \Google_Service_YouTube_PlaylistItem $videoYoutube */
                $snippet = $videoYoutube->getSnippet();
                /** @var Video $video */
                $video = $videoRepository->findOneBy([
                    'code' => $snippet->getResourceId()->getVideoId()
                ]);

                if (null === $video) {
                    $video = new Video();
                    $video->setTitle($snippet->getTitle());
                    $video->setCode($snippet->getResourceId()->getVideoId());
                    $video->setThumbnail($snippet->getThumbnails()->getHigh()->getUrl());
                    $video->setPlaylist($playlist);
                    $em->persist($video);
                }
            }
        }
        $em->flush();
    }
}
