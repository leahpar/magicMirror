<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('ddate', array($this, 'ddate')),
            new TwigFilter('weatherIcon', array($this, 'weatherIcon')),
        );
    }

    /**
     * https://stackoverflow.com/questions/25622370/php-how-to-check-if-a-date-is-today-yesterday-or-tomorrow
     * @param string $str
     * @param string|null $format
     * @return null
     */
    public function ddate(string $str, string $format = null)
    {
        $date = new \DateTime($str);
        $currentTime = strtotime('today');
        // Reset time to 00:00:00
        $timestamp = strtotime(date('Y-m-d 00:00:00', $date->getTimestamp()));
        $days = round(($timestamp - $currentTime) / 86400);

        switch ($days) {
            case '0';
                return "aujourd'hui";
                break;
            case '-1';
                return 'hier';
                break;
            case '1';
                return 'demain';
                break;
        }
        return $format ? $date->format($format) : null;
    }
    
    public function weatherIcon(string $code)
    {
        if ($code == "01d") {
        return "weather-clear.png";
        } elseif ($code == "02d") {
            return "weather-few-clouds.png";
        } elseif ($code == "03d") {
            return "weather-clouds.png";
        } elseif ($code == "04d") {
            return "weather-haze.png";
        } elseif ($code == "09d") {
            return "weather-showers-day.png";
        } elseif ($code == "10d") {
            return "weather-rain-day.png";
        } elseif ($code == "11d") {
            return "weather-storm-day.png";
        } elseif ($code == "13d") {
            return "weather-snow-scattered-day.png";
        } elseif ($code == "50d") {
            return "weather-mist.png";
        } elseif ($code == "01n") {
            return "weather-clear-night.png";
        } elseif ($code == "02n") {
            return "weather-few-clouds-night.png";
        } elseif ($code == "03n") {
            return "weather-clouds-night.png";
        } elseif ($code == "04n") {
            return "weather-haze.png";
        } elseif ($code == "09n") {
            return "weather-showers-night.png";
        } elseif ($code == "10n") {
            return "weather-rain-night.png";
        } elseif ($code == "11n") {
            return "weather-storm-night.png";
        } elseif ($code == "13n") {
            return "weather-snow-scattered-night.png";
        } elseif ($code == "50n") {
            return "weather-mist.png";
        }
        else {
            return null;
        }
    }
}
