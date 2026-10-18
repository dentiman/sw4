<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-11-25
 * Time: 17:56
 */

namespace App\DataFeedApp\Bar\Read\Sources;


use App\DataFeedApp\Bar\Model\BarData;
use App\DataFeedApp\Bar\Read\Exception\NoDataException;
use App\DataFeedApp\Bar\Storage\DailyBarStorage;
use App\DataFeedApp\Bar\TimeFrame\BarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\DailyBarTimeFrame;
use App\DataFeedApp\Bar\TimeFrame\MinuteBarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\WeeklyBarTimeFrame;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class CacheBarSource implements BarSourcesInterface
{

    /**
     * @param string $ticker
     * @param string $timeFrame
     * @param \DateTime|null $startTime
     * @param \DateTime|null $endTime
     * @param bool $disablePremarket
     * @return BarData
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getBars(
        string $ticker,
        BarTimeFrameInterface $timeFrame,
        ?\DateTime $startTime = null,
        ?\DateTime $endTime = null,
        bool $disablePremarket = true
    ): BarData
    {
        $cache = $this->getCacheClient();
        $cachedContent = $cache->getItem($ticker . $timeFrame->getId());
        if (!$cachedContent->isHit()) {
            throw new \Exception('Content not exist ');
        }

        $barData = new BarData(json_decode($cachedContent->get(), true));

        $barData->source = 'C';
        if (
            $disablePremarket && $timeFrame instanceof MinuteBarTimeFrameInterface
        ) {
            $barData->removePremarketData();
        }

        if(count($barData->time)<5)  throw new NoDataException();;
        return $barData;

    }

    public function support(BarTimeFrameInterface $timeFrame): bool
    {
        if (!in_array($timeFrame->getId(), ['5', '1', '15','d'])) return false;
        return true;
    }

    /**
     * @return AbstractAdapter
     */
    public static function getCacheClient()
    {
        //TODO:: get redis host from config or env
        return  new RedisAdapter(
            RedisAdapter::createConnection(
                'redis://redis:6379'
            ),
            $namespace = '',
            $defaultLifetime = 120
        );

        // return new FilesystemAdapter('chart',120);
    }

}
