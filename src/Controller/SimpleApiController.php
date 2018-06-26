<?php

namespace App\Controller;


use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SimpleApiController extends AbstractController
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


    public function changeAction()
    {
        $change = [];
        $url = "https://api.coindesk.com/v1/bpi/currentprice/EUR.json";
        $bt = $this->getData($url);
        $change[] = [
            "currency" => "Bitcoin",
            "code" => "Bt",
            "symbol" => "฿",
            "value" => $bt["bpi"]["EUR"]["rate_float"]
        ];


        $url = "http://free.currencyconverterapi.com/api/v5/convert?q=USD_EUR&compact=y";
        $usd = $this->getData($url);
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

    /**
     * @param $url
     * @return mixed
     */
    private function getData($url)
    {
        $key = md5($url);
        $content = $this->cache->get($key);
        if (!$content) {
            $content = $this->fetch($url);
            $this->cache->set($key, $content, rand(150, 300));
        }
        return json_decode($content, true);
    }

    /**
     * @param $url
     * @return mixed
     */
    private function fetch($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //curl_setopt_array($ch, $this->curlOptions);

        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }
}