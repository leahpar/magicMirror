<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhueController extends AbstractController
{

    // https://github.com/sqmk/Phue


    public function phueAction()
    {
        $client = new \Phue\Client('10.0.1.31', 'toto');

        $lights = $client->getLights();
        $groups = $client->getGroups();

        foreach ($groups as $groupId => $group) {
            dump("GROUP #" . $group->getId() . " " . $group->getName());

            foreach ($group->getLightIds() as $lightId) {
                $light = $lights[$lightId];
                $light->group = $groupId;
                dump("\tLIGHT #" . $lightId . " " . $light->getName() . "[" . ($light->isOn() ? 'ON' : 'OFF') . "]");
            }
        }

        dump("NO GROUP");
        foreach ($lights as $lightId => $light) {
            if (!property_exists($light, 'group')) {
                dump("\tLIGHT #" . $lightId);
                dump($light->getName());
                dump("[" . ($light->isOn() ? 'ON' : 'OFF') . "]");
            }
        }
        
    }
}


