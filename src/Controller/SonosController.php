<?php

namespace App\Controller;


use duncan3dc\Sonos\Network;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SonosController extends AbstractController
{

    public function SonosAction()
    {
        $sonos = new Network;
        $controller = $sonos->getControllerByRoom("Sonos");
        $track = $controller->getStateDetails();

//        dump("Now Playing: {$track->title} from {$track->album} by {$track->artist}");
//        dump("Running Time: {$track->position} / {$track->duration}");

        return $this->render("cards/sonos.html.twig", [
            "track" => $track
        ]);
    }
}