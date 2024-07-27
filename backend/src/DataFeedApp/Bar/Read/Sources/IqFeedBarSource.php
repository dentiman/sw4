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
use App\DataFeedApp\Bar\TimeFrame\BarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\DailyBarTimeFrame;
use App\DataFeedApp\Bar\TimeFrame\MinuteBarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\WeeklyBarTimeFrame;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class IqFeedBarSource implements BarSourcesInterface
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
    ) : BarData
    {

        $url = 'http://168.119.129.37:9390/feeddone/chart/'.$ticker.'/'.$timeFrame->getId().'/'.
            $startTime->getTimestamp().'/' .$endTime->getTimestamp();

     //   http://168.119.129.37:9390/feeddone/chart/AAPL/5/1609762539/1609848939

        $client = HttpClient::create(['timeout'=>1.5]);
        $response = $client->request('GET', $url);
        $rows = explode("\r\n", trim($response->getContent()));

        $barData = new BarData();

        if(count($rows)>2) {

            foreach ($rows as $row) {
                $field = explode(',', trim($row));
                if (
                    $timeFrame instanceof DailyBarTimeFrame ||
                    $timeFrame instanceof WeeklyBarTimeFrame
                ) {
                    $barData->time[] = new \DateTime( $field[0], new \DateTimeZone('America/New_York'));
                    $barData->close[] =  $field[4] * 1;
                    $barData->open[] =  $field[3] * 1;
                    $barData->high[] =  $field[1] * 1;
                    $barData->low[] =  $field[2] * 1;
                    $barData->volume[] =  $field[5] * 1;
                } else {
                    $new_time = new \DateTime( $field[0], new \DateTimeZone('America/New_York'));
                    $new_time->modify("-".($timeFrame->getId())." min");
                    $barData->time[] = $new_time;
                    $barData->close[] =  $field[4] * 1;
                    $barData->open[] =  $field[3] * 1;
                    $barData->high[] =  $field[1] * 1;
                    $barData->low[] =  $field[2] * 1;
                    $barData->volume[] =  $field[6] * 1;
                }
            }
            $barData->source = 'I';

            if(in_array($timeFrame->getId(),['5','1','15','d'])) {
                $cache = CacheBarSource::getCacheClient();
                $cachedContent = $cache->getItem($ticker.$timeFrame->getId());;
                $cachedContent->set(json_encode($barData->toArray()));
                $cache->save($cachedContent);
            }

            if (
                $disablePremarket && $timeFrame instanceof MinuteBarTimeFrameInterface
              ) {
                $barData->removePremarketData();
            }

            return $barData;
        }
        throw new NoDataException();
    }

    public function support(BarTimeFrameInterface $timeFrame): bool
    {
        return true;
    }

}
