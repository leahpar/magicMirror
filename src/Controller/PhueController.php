<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PhueController extends AbstractController
{

    // https://github.com/sqmk/Phue

    /**
     * @Route("phue")
     */
    public function phueAction()
    {
        $client = new \Phue\Client('192.168.1.10', 'shnEkybxuPWnCv1RDb0EVzNQKzW93LsieLl4XtUs');
        $lights = $client->getLights();
        $groups = $client->getGroups();

        foreach ($groups as $groupId => $group) {
            //dump("GROUP #" . $group->getId() . " " . $group->getName());

            $group->lightOn = false;
            foreach ($group->getLightIds() as $lightId) {
                $light = $lights[$lightId];
                $light->group = $groupId;
                $group->lightOn |= $light->isOn();
                //dump("\tLIGHT #" . $lightId . " " . $light->getName() . "[" . ($light->isOn() ? 'ON' : 'OFF') . "]");
            }
        }

        //dump("NO GROUP");
        /*
        foreach ($lights as $lightId => $light) {
            if (!property_exists($light, 'group')) {
                //dump("\tLIGHT #" . $lightId);
                //dump($light->getName());
                //dump("[" . ($light->isOn() ? 'ON' : 'OFF') . "]");
            }
        }
        */

        return $this->render("cards/phue.html.twig", [
            "groups" => $groups,
            "lights" => $lights
        ]);
    }
}


