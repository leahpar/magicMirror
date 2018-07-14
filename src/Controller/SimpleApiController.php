<?php

namespace App\Controller;

use App\Services\SimpleApiService;
use Psr\SimpleCache\CacheException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SimpleApiController extends AbstractController
{
    /**
     * @var SimpleApiService
     */
    private $api;

    /**
     * SimpleApiController constructor.
     * @param SimpleApiService $api
     */
    public function __construct(SimpleApiService $api)
    {
        $this->api = $api;
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeAction()
    {
        try {
            $change = [];
            $url = "https://api.coindesk.com/v1/bpi/currentprice/EUR.json";
            $bt = $this->api->getData($url, rand(150, 300));
            $change[] = [
                "currency" => "Bitcoin",
                "code" => "Bt",
                "symbol" => "฿",
                "value" => $bt["bpi"]["EUR"]["rate_float"]
            ];

            $url = "http://free.currencyconverterapi.com/api/v5/convert?q=USD_EUR&compact=y";
            $usd = $this->api->getData($url, rand(150, 300));
            $change[] = [
                "currency" => "US Dollar",
                "code" => "USD",
                "symbol" => "$",
                "value" => $usd["USD_EUR"]["val"]
            ];

            return $this->render("cards/change.html.twig", [
                "change" => $change
            ]);
        }
        catch (CacheException $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Erreur de cache API taux change"
            ]);
        }
        catch (\Exception $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Echec de connexion API taux change"
            ]);
        }
    }

}