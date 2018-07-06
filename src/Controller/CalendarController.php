<?php

namespace App\Controller;


use ICal\ICal;
use Psr\SimpleCache\CacheException;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
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



    public function fifaAction()
    {
        $url = "https://www.walfoot.be/soccer/calendar/competition/competition_214_fra.ics";
        try {

            $ical = $this->cache->get("ical-fifa", null);
            if (!$ical) {

                $ical = new ICal($url, array(
                    'defaultSpan' => 2,     // Default value
                    'defaultTimeZone' => 'UTC',
                    'defaultWeekStart' => 'MO',  // Default value
                    'disableCharacterReplacement' => false, // Default value
                    'skipRecurrence' => false, // Default value
                    'useTimeZoneWithRRules' => false, // Default value
                ));

                $this->cache->set("ical-fifa", $ical, 300);
            }
            return $this->render("cards/fifa.html.twig", [
                'events' => $ical->eventsFromInterval('1 week'),
            ]);
        }
        catch (CacheException $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Erreur de cache Ican"
            ]);
        }
        catch (\Exception $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Echec de connexion Ical"
            ]);
        }
    }

}