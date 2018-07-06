<?php


namespace App\Controller;


use DPRMC\IEXTrading\IEXTrading;
use DPRMC\IEXTrading\Responses\IEXTradingResponse;
use Psr\SimpleCache\CacheException;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TradingController extends AbstractController
{
    /**
     * @var CacheInterface
     */
    private $cache;


    /**
     * TradingController constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function bourseAction()
    {
        $codes = ['AAPL', 'GOOGL', 'AMZN', 'MSFT', 'FB'];

        try {
            $stocks = [];

            foreach ($codes as $code) {
                $stocks[$code] = $this->getStockQuote($code);
                //$stocks[$code]["stats"] = $this->getStockStats($code);
            }
        }
        catch (CacheException $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Erreur de cache Bourse"
            ]);
        }
        catch (\Exception $e) {
            return $this->render("cards/error.html.twig", [
                "message" => "Echec de connexion Bourse"
            ]);
        }

        return $this->render("cards/bourse.html.twig", [
            "stocks" => $stocks,
        ]);
    }

    /**
     * @param string $code
     * @return IEXTradingResponse
     * @throws \DPRMC\IEXTrading\Exceptions\UnknownSymbol
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getStockQuote(string $code)
    {
        $stockQuote = $this->cache->get("iex-$code-stockquote");

        if (!$stockQuote) {
            // https://iextrading.com/developer/docs/#quote
            $stockQuote = IEXTrading::stockQuote($code);

            $this->cache->set("iex-$code-stockquote", $stockQuote, rand(1000, 1500));
        }

        return $stockQuote;
    }

    /**
     * @param string $code
     * @return IEXTradingResponse
     * @throws \DPRMC\IEXTrading\Exceptions\UnknownSymbol
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getStockStats(string $code)
    {
        $stockStats = $this->cache->get("iex-$code-stockstats");

        if (!$stockStats) {
            // https://iextrading.com/developer/docs/#key-stats
            $stockStats = IEXTrading::stockStats($code);

            $this->cache->set("iex-$code-stockstats", rand(1000, 1500));
        }
        return $stockStats;
    }
}