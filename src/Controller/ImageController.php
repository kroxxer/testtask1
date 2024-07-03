<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{

    private const MEGABYTE = 1048576;
    #[Route('/', name: 'app_image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig');
    }

    #[Route('/search', name: 'app_search_image')]
    public function searchImage(Request $request): Response
    {
        set_time_limit(0);
        $postData = json_decode($request->getContent());
        $url = $postData[0][1];

        if ($url === '') {
            return new Response();
        }

        $selector = "//img[@src]";

        $page = file_get_contents($url);

        $crawler = new Crawler($page, useHtml5Parser: true);
        $images = $crawler->evaluate($selector)->each(function (Crawler $node): string {

            $urlImage = $node->attr('src');
            if (str_starts_with($urlImage, 'https:'))
                return $urlImage;
            else
                return 'https://mayak.travel' . $urlImage;
        });
        $imagesSize = 0;
        $images = array_unique($images);

        foreach($images as $image){
            $imagesSize += get_headers($image, true)['Content-Length'] / self::MEGABYTE;
        }

        $imagesAmount = count($images);

        $response = $this->renderView('image/images.html.twig', [
            'images' => $images,
            'imageAmount' => $imagesAmount,
            'imagesSize' => $imagesSize
        ]);

        return new Response($response);
    }
}
