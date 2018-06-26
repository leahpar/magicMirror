<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {

        /*
        try {
            $owm = new OpenWeatherMap(getenv("OWM_API_KEY"));

            $weather = $cache->get("weather", null);
            if (!$weather) {
                $weather = $owm->getWeather('Rouen,FR', "metric", "fr");
                $cache->set("weather", $weather, 3600);
            }

            $forecast = $cache->get("forecast", null);
            if (!$forecast) {
                $forecast = $owm->getWeatherForecast('Rouen,FR', "metric", "fr", null, 5);
                $cache->set("forecast", $forecast, 8000);
            }

        }
        catch (\Exception $e) {
            $weather = null;
            $forecast = null;
        }
        */



        return $this->render('index.html.twig', []);
    }


}
