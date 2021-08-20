<?php

namespace App\Controller;

use App\Services\CovidApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(CovidApiService $dataCovid): Response
    {
        $urlBase ='http://image.tmdb.org/t/p/w500';
        $response = $dataCovid->getFranceData();
        // dd($response);
        return $this->render('home/index.html.twig', [
            'response' => $response['results'],
            'urlBase' => $urlBase
        ]);
    }
}
