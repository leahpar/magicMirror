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


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function statusAction()
    {
        $client = new Client("10.0.0.10");

        $database = $client->selectDB('test');

        try {
            // TODO : phpstorm datasource influxDB
            $result = $database->query('SELECT sum(value) FROM elec WHERE time > now() - 1h');
            $points = $result->getPoints();
            $status["elec"] = $points[0]["sum"];
        }
        catch (\Exception $e) {
            $status["elec"] = false;
        }

        try {
            $result = $database->query('SELECT mean(value) FROM temperature WHERE time > now() - 1h GROUP BY location');
            foreach ($result->getSeries() as $serie) {
                $status["temp"][$serie["tags"]["location"]] = $serie["values"][0][1];
            }
        }
        catch (\Exception $e) {
            $status["temp"] = false;
        }

        return $this->render("cards/domotic-status.html.twig", [
            "status" => $status
        ]);
    }

}
