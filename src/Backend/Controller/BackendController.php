<?php

namespace App\Backend\Controller;

use App\Backend\Manager\VideoManager;
use App\Core\Entity\Video;
use App\Core\Service\YoutubeService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends Controller
{
    /**
     * @Route("/", name="backend_index")
     */
    public function index()
    {
        return $this->render('backend/index.html.twig');
    }

    /**
     * @Route("/playlist", name="backend_playlist")
     */
    public function playlist()
    {
        $videos = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findBy([], ['enable' => 'DESC']);

        return $this->render('backend/list.html.twig', [
            'videos' => $videos
        ]);
    }

    /**
     * @Route("/video/enable/{id}", methods={"PUT"})
     * @param Video $video
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function enableVideo(Video $video, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            try {
                $data = json_decode($request->getContent(), true);
                $this->get(VideoManager::class)->enableVideo($data, $video);

                return $this->json(['success' => 1]);
            } catch (\Exception $exception) {
                return $this->json(['success' => 0], Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json(['success' => 0], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/add_video_form", name="backend_add_video_form")
     */
    public function addVideoForm()
    {
        return $this->render('backend/add_video_form.html.twig');
    }

    /**
     * @Route("/video/add", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addVideo(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            try {
                $data = json_decode($request->getContent(), true);
                $this->get(VideoManager::class)->addVideo($data);

                return $this->json(['success' => 1]);
            } catch (\Exception $exception) {
                return $this->json(['success' => 0], Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json(['success' => 0], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/search/{string}", methods={"GET"})
     * @param $string
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function youtubeSearch($string, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            try {
                $videos = $this->get(YoutubeService::class)->search($string);

                return $this->json([
                    'success' => 1,
                    'content' => $this->renderView('backend/search.html.twig', [
                        'videos' => $videos
                    ])
                ]);
            } catch (\Exception $exception) {
                return $this->json(['success' => 0], Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json(['success' => 0], Response::HTTP_BAD_REQUEST);
    }
}
