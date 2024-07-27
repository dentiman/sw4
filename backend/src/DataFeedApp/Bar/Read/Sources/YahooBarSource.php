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
use Symfony\Component\HttpClient\HttpClient;

class YahooBarSource implements BarSourcesInterface
{
    /**
     * @param string $ticker
     * @param BarTimeFrameInterface $timeFrame
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
        $url = 'https://query1.finance.yahoo.com/v7/finance/chart/' . $ticker .
            '?&interval=' . $timeFrame->yahooTimeFrameId()
            . '&period1=' . $startTime->getTimestamp()
            . '&period2=' . $endTime->getTimestamp();

        $client = HttpClient::create();
        $response = $client->request('GET', $url);
        $data = json_decode(trim($response->getContent()), true);


        $barData = new BarData();
        $barData->open = array_reverse($data['chart']['result']['0']['indicators']['quote']['0']['open']);
        $barData->low = array_reverse($data['chart']['result']['0']['indicators']['quote']['0']['low']);
        $barData->high = array_reverse($data['chart']['result']['0']['indicators']['quote']['0']['high']);
        $barData->close = array_reverse($data['chart']['result']['0']['indicators']['quote']['0']['close']);
        $barData->volume = array_reverse($data['chart']['result']['0']['indicators']['quote']['0']['volume']);
        $times = [];
        foreach ($data['chart']['result']['0']['timestamp'] as $timestamp) {
            $time = new \DateTime('now', new \DateTimeZone('America/New_York'));
            $time->setTimestamp($timestamp);
            $times [] = $time;
        }
        $barData->time = array_reverse($times);
        $barData->source = 'Y';
        return $barData;



    }

    public function support(BarTimeFrameInterface $timeFrame): bool
    {
        if(!in_array($timeFrame->getId(),['d'])) return  false;
        return true;
    }

}
