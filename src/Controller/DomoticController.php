<?php

namespace App\Controller;


use InfluxDB\Client;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DomoticController extends AbstractController
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * MeteoController constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }


    public function statusAction()
    {
        $client = new Client("10.0.0.10");

        $database = $client->selectDB('test');

        $result = $database->query('SELECT sum(value) FROM elec WHERE time > now() - 1h');
        $points = $result->getPoints();
        $status["elec"] = $points[0]["sum"];

        $result = $database->query('SELECT mean(value) FROM temperature WHERE time > now() - 1h GROUP BY location');
        foreach ($result->getSeries() as $serie) {
            $status["temp"][$serie["tags"]["location"]] = $serie["values"][0][1];
        }

        return $this->render("cards/domotic-status.html.twig", [
            "status" => $status
        ]);
    }

}