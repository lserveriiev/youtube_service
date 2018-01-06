<?php

namespace App\Backend\Manager;

use App\Core\Entity\Video;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VideoManager
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     * @param Video $video
     * @return Video
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws BadRequestHttpException
     */
    public function enableVideo(array $data, Video $video): Video
    {
        if (!isset($data['enable'])) {
            throw new BadRequestHttpException();
        }

        $video->setEnable($data['enable']);

        $this->entityManager->persist($video);
        $this->entityManager->flush($video);

        return $video;
    }

    /**
     * @param array $data
     * @return Video
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addVideo(array $data): Video
    {
        if (!isset($data['title'], $data['thumbnail'], $data['code'])) {
            throw new BadRequestHttpException();
        }

        $video = new Video();
        $video->setCode($data['code']);
        $video->setTitle($data['title']);
        $video->setThumbnail($data['thumbnail']);

        $this->entityManager->persist($video);
        $this->entityManager->flush($video);

        return $video;
    }
}
