<?php

namespace App\Controller;


use App\Services\SimpleApiService;
use Psr\SimpleCache\CacheException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MeteoController extends AbstractController
{

    const API_URL = "http://dataservice.accuweather.com/";

    /**
     * @var SimpleApiService
     */
    private $api;

    /**
     * MeteoController constructor.
     * @param SimpleApiService $api
     */
    public function __construct(SimpleApiService $api)
    {
        $this->api = $api;
    }


    public function currentMeteoAction()
    {
        try {
            $url = $this->buildUrl("current");
            $meteo = $this->api->getData($url, rand(1000, 1500))[0];

            $meteo["WeatherIcon"] = $this->getIcon($meteo["WeatherIcon"]);

            $url = $this->buildUrl("forecast");
            $forecast = $this->api->getData($url, rand(1500, 2000));
            //dump($forecast); die();
            $meteo["sunrise"] = $forecast["DailyForecasts"][0]["Sun"]["Rise"];
            $meteo["sunset"] = $forecast["DailyForecasts"][0]["Sun"]["Set"];

            return $this->render("cards/meteo-current.html.twig", [
                "meteo" => $meteo
            ]);
        }
        catch (CacheException $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Erreur de cache API taux change"
            ]);
        }
        catch (\Exception $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Echec de connexion API météo"
            ]);
        }
    }

    /**
     * @return Response
     */
    public function forecastMeteoAction()
    {
        try {
            $url = $this->buildUrl("forecast");
            $meteo = $this->api->getData($url, rand(1500, 2000));

            foreach ($meteo["DailyForecasts"] as $k => $v) {
                $meteo["DailyForecasts"][$k]["Day"]["WeatherIcon"] = $this->getIcon($v["Day"]["Icon"]);
                $meteo["DailyForecasts"][$k]["Night"]["WeatherIcon"] = $this->getIcon($v["Night"]["Icon"]);
            }

            return $this->render("cards/meteo-forecast.html.twig", [
                "meteo" => $meteo
            ]);
        }
        catch (CacheException $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Erreur de cache API taux change"
            ]);
        }
        catch (\Exception $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Echec de connexion API météo"
            ]);
        }
    }

    /**
     * https://developer.accuweather.com/weather-icons
     * @param string $iconCode
     * @return string
     */
    private function getIcon(string $iconCode) {
        $icon = "";
        switch ($iconCode) {
            case 1:
            case 2:
            case 3:
                $icon = "weather-clear.png";
                break;
            case 4:
            case 5:
            case 20:
            case 21:
                $icon = "weather-few-clouds.png";
                break;
            case 6:
            case 19:
                $icon = "weather-haze.png";
                break;
            case 7:
            case 8:
                $icon = "weather-clouds.png";
                break;
            case 11:
                $icon = "weather-fog.png";
                break;
            case 12:
            case 18:
                $icon = "weather-showers-scattered-night.png";
                break;
            case 13:
            case 14:
                $icon = "weather-rain-day.png";
                break;
            case 15:
                $icon = "weather-storm.png";
                break;
            case 16:
            case 17:
                $icon = "weather-storm-day.png";
                break;
            case 22:
            case 23:
                $icon = "weather-snow-scattered-day.png";
                break;
            case 24:
            case 25:
            case 26:
                $icon = "weather-snow.png";
                break;
            case 29:
                $icon = "weather-snow-rain.png";
                break;
            case 32:
                $icon = "weather-wind.png";
                break;
            case 33:
            case 34:
                $icon = "weather-clear-night.png";
                break;
            case 35:
            case 36:
                $icon = "weather-clouds-night.png";
                break;
            case 37:
                $icon = "weather-fog.png";
                break;
            case 38:
                $icon = "weather-clouds.png";
                break;
            case 39:
            case 40:
                $icon = "weather-rain-night.png";
                break;
            case 41:
            case 42:
                $icon = "weather-showers-night.png";
                break;
            case 43:
            case 44:
                $icon = "weather-snow-scattered-night";
                break;
        }
        return $icon;
    }


    /**
     * @param string $type
     * @return string
     */
    private function buildUrl(string $type) : string
    {
        $url = self::API_URL;
        if ($type == "current") {
            $url .= "currentconditions/v1/";
        }
        elseif ($type == "forecast") {
            $url .= "forecasts/v1/daily/5day/";
        }

        $url .= getenv("ACW_LOCATION_ID");
        $url .= "?apikey=" . getenv("ACW_API_KEY");
        $url .= "&language=fr";
        $url .= "&metric=true";
        $url .= "&details=true";

        return $url;
    }
}


/* OLD API
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