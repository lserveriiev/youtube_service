<?php

namespace App\Front\Controller;

use App\Core\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class YoutubeController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $videos = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findBy(['enable' => true]);

        return $this->render('front/index.html.twig', [
            'videos' => $videos
        ]);
    }

    /**
     * @Route("/view/{code}", name="view_video")
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Video $video)
    {
        $videos = $this->getDoctrine()
            ->getRepository(Video::class)
            ->getAllWithoutVideo($video);

        return $this->render('front/view.html.twig', [
            'video' => $video,
            'videos' => $videos
        ]);
    }
}