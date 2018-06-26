<?php

namespace App\Controller;


use ICal\ICal;
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
        $ical = $this->cache->get("ical-fifa", null);
        if (!$ical) {
            $ical = new ICal('https://www.walfoot.be/soccer/calendar/competition/competition_214_fra.ics', array(
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
            'events' =>  $ical->eventsFromInterval('1 week'),
        ]);
    }

}