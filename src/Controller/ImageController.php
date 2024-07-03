<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    #[Route('/', name: 'app_image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig');
    }

    #[Route('/search', name: 'app_search_image')]
    public function searchImage(Request $request): Response
    {
        $postData = json_decode($request->getContent());
        $url = $postData[0][1];

        $page = file_get_contents($url);

        $response = $this->renderView('image/images.html.twig', [
            'images' => $images,
        ]);

        return $this->render('image/index.html.twig', [
            'controller_name' => 'ImageController',
        ]);
    }
}
