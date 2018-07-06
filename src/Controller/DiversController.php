<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiversController extends AbstractController
{
    public function horlogeAction()
    {
        return $this->render("cards/horloge.html.twig");
    }
}