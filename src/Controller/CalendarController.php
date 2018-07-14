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



    public function icalAction()
    {
        $calendars = [
            "Fifa2018" => "https://www.walfoot.be/soccer/calendar/competition/competition_214_fra.ics",
            "Perso" => "https://calendar.google.com/calendar/ical/leahpar%40gmail.com/private-5520f0f62e17bc6bbbf3c6045174f181/basic.ics"
        ];
        $events = [];

        try {

            foreach ($calendars as $cal => $url) {
                $ical = $this->cache->get("ical-".$cal, null);
                if (!$ical) {

                    $ical = new ICal($url, array(
                        'defaultSpan' => 2,     // Default value
                        'defaultTimeZone' => 'UTC',
                        'defaultWeekStart' => 'MO',  // Default value
                        'disableCharacterReplacement' => false, // Default value
                        'skipRecurrence' => false, // Default value
                        'useTimeZoneWithRRules' => false, // Default value
                    ));

                    $this->cache->set("ical".$cal, $ical, 300);
                }
                $events = array_merge($events, $ical->eventsFromInterval('10 days'));
            }

            // Tri par date
            usort($events, function ($a, $b) {
                return $a->dtstart <=> $b->dtstart;
            });

            return $this->render("cards/calendar.html.twig", [
                'events' => $events
            ]);
        }
        catch (CacheException $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Erreur de cache Calendar"
            ]);
        }
        catch (\Exception $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Echec de connexion Calendar"
            ]);
        }
    }

}