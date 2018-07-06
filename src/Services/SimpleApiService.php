<?php

namespace App\Services;


use Psr\SimpleCache\CacheException;
use Psr\SimpleCache\CacheInterface;

class SimpleApiService
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param $url
     * @param int $lifetime
     * @return mixed
     * @throws \Exception
     * @throws CacheException
     */
    public function getData($url, int $lifetime = 0)
    {
        $key = md5($url);
        $content = $this->cache->get($key);
        if (!$content) {
            $content = $this->fetch($url);
            if (!$content) {
                throw new \Exception();
            }
            $this->cache->set($key, $content, $lifetime);
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
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }
}