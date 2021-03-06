<?php

namespace App\Controller;


use Psr\SimpleCache\CacheException;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RssController extends AbstractController
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

    public function rssAction()
    {
        try {
            $feeds = [
                "Le Monde" => "https://www.lemonde.fr/rss/une.xml",
                "Journal du Geek" => "https://www.journaldugeek.com/feed/"
            ];
            $entries = [];
            // Fetch all items
            foreach ($feeds as $source => $feed) {
                $entries = array_merge($entries, $this->getEntries($source, $feed));
            }
            // Sort items by pubDate
            // Nope, keep random sort
            /*
            usort($entries, function ($feed1, $feed2) {
                return strtotime($feed2->pubDate) - strtotime($feed1->pubDate);
            });
            */

            return $this->render("cards/rss.html.twig", [
                'feeds' => $entries
            ]);
        }
        catch (CacheException $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Erreur de cache flux RSS"
            ]);
        }
        catch (\Exception $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Echec de connexion flux RSS"
            ]);
        }
    }

    /**
     * @param string $source
     * @param string $url
     * @return array|mixed
     * @throws \Exception
     * @throws CacheException
     */
    private function getEntries(string $source, string $url)
    {
        $key = md5($url);
        $entries = $this->cache->get($key);
        if (!$entries) {
            $xml = simplexml_load_file($url);
            if (!$xml) {
                throw new \Exception();
            }
            $entries = array_map(
                function($a) use ($source) {
                    $a["source"] = $source;
                    return $a;
                },
                $xml->xpath("//item")
            );
            $this->cache->set($key, $entries, 300);
        }
        return $entries;
    }

}