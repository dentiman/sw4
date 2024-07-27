<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 22.12.16
 * Time: 11:44
 */

namespace App\DataFeedApp\Bar\Model;

use App\DataFeedApp\Bar\Read\BarReader;
use App\DataFeedApp\Bar\Read\Sources\FileBarSource;
use App\DataFeedApp\Bar\Read\Sources\IqFeedBarSource;
use App\DataFeedApp\Bar\Storage\DailyBarStorage;

use App\DataFeedApp\Helper;
use App\Entity\Chart\ChartLayout;

class BarData
{
    public $open;

    public $high;

    public $low;

    public $close;

    public $volume;

    /**
     * @var \DateTimeInterface[] $time
     */
    public $time;

    public $source = null;

    public $maxPrice;

    public $minPrice;

    public $priceRange;

    public $maxVolume;

    public $movingAverageData = [];

    public $horizontalLines = [];

    public function __construct(array $data = [])
    {
        if (empty($data) == false) {
            $this->open = $data['o'];
            $this->high = $data['h'];
            $this->low = $data['l'];
            $this->close = $data['c'];
            $this->volume = $data['v'];
            foreach ($data['t'] as $iso) {
                $this->time[] =  new \DateTime($iso);
            }
        }
    }

    public function toArray()
    {
        $data['o'] = $this->open;
        $data['h'] = $this->high;
        $data['l'] = $this->low;
        $data['c'] = $this->close;
        $data['v'] = $this->volume;
        $data['t'] =[];

        /** @var \DateTime $dateTime */
        foreach ($this->time as $dateTime) {
            $data['t'][]  = $dateTime->format('c');
        }
        return $data;
    }

    public function calculateForChart(ChartLayout $chartLayout)
    {
        if($chartLayout->getTimeFrame() == 'd')   $this->calculateMA($chartLayout);



        //todo:: need to calculate sma before slice
        $this->time = array_slice($this->time, 0, $chartLayout->getBarsCount());
        $this->open = array_slice($this->open, 0, $chartLayout->getBarsCount());
        $this->high = array_slice($this->high, 0, $chartLayout->getBarsCount());
        $this->low = array_slice($this->low, 0, $chartLayout->getBarsCount());
        $this->close = array_slice($this->close, 0, $chartLayout->getBarsCount());
        $this->volume = array_slice($this->volume, 0, $chartLayout->getBarsCount());

        $this->maxPrice = max($this->high);
        $this->minPrice = min($this->low);
        $this->priceRange = $this->maxPrice - $this->minPrice;
        $this->maxPrice = $this->maxPrice + $this->priceRange / 26;
        $this->maxVolume = max($this->volume);

        if(
            $chartLayout->getTimeFrame() != 'd' && $chartLayout->getTimeFrame() != 'w' &&
            (
                $chartLayout->getLinesOpen() || $chartLayout->getLinesHigh() ||
                $chartLayout->getLinesLow() || $chartLayout->getLinesClose()
            )

        )
            $this->calculateLines($chartLayout);
    }

    public function removePremarketData()
    {
        $tmp = $this->time;
        foreach ($tmp as $index => $dateTime) {
            if($dateTime->format('Hi')*1 >= 1600 ||  $dateTime->format('Hi')*1 < 930 ) {
                unset($this->time[$index]);
                unset($this->open[$index]);
                unset($this->high[$index]);
                unset($this->low[$index]);
                unset($this->close[$index]);
                unset($this->volume[$index]);
            }
        }
    }


    /**
     * расчет данных для отрисовки скользящих средних
     * @param ChartLayout $chartLayout
     */
    public function calculateMA(ChartLayout $chartLayout)
    {
        if ( $chartLayout->getSma1()) {

            $this->movingAverageData[] = [
                'name' => 'sma ' . $chartLayout->getSma1(),
                'data' => $this->getSMA($this->close, $chartLayout->getSma1(), $chartLayout->getBarsCount()),
                'color' => $chartLayout->getSma1Color()
            ];
        }

        if ( $chartLayout->getSma2()) {

            $this->movingAverageData[] = [
                'name' => 'sma ' . $chartLayout->getSma2(),
                'data' => $this->getSMA($this->close, $chartLayout->getSma2(), $chartLayout->getBarsCount()),
                'color' =>$chartLayout->getSma2Color()
            ];
        }

        if ($chartLayout->getSma3()) {

            $this->movingAverageData[] = [
                'name' => 'sma ' . $chartLayout->getSma3(),
                'data' => $this->getSMA($this->close, $chartLayout->getSma3(), $chartLayout->getBarsCount()),
                'color' => $chartLayout->getSma3Color()
            ];
        }

        if ( $chartLayout->getEma1()) {

            $this->movingAverageData[] = [
                'name' => 'ema ' . $chartLayout->getEma1(),
                'data' => $this->getEMA($this->close, $chartLayout->getEma1(), $chartLayout->getBarsCount()),
                'color' => $chartLayout->getEma1Color()
            ];
        }

        if ( $chartLayout->getEma2()) {

            $this->movingAverageData[] = [
                'name' => 'ema ' . $chartLayout->getEma1(),
                'data' => $this->getEMA($this->close, $chartLayout->getEma1(), $chartLayout->getBarsCount()),
                'color' => $chartLayout->getEma2Color()
            ];
        }

        if ( $chartLayout->getEma3()) {

            $this->movingAverageData[] = [
                'name' => 'ema ' . $chartLayout->getEma3(),
                'data' => $this->getEMA($this->close, $chartLayout->getEma3(), $chartLayout->getBarsCount()),
                'color' => $chartLayout->getEma3Color()
            ];
        }

    }


    public function getSMA($data, $period, $bars_count)
    {
        $A = [];
        $count = count($data) - $period;
        for ($i = 0; $i <= $count; $i++) {
            $A[] = round(array_sum(array_slice($data, $i, $period)) / $period, 2);
        }
        return array_slice($A, 0, $bars_count);
    }

    public function getEMA($data, $period, $bars_count)
    {
        $A = [];
        $data = array_reverse($data);
        for ($i = 0; $i < count($data); $i++) {
            if ($i == $period - 1) {
                $A[] = round(array_sum(array_slice($data, 0, $period)) / $period, 2);
            } else {
                $A[] = $data[$i] * 2 / ($period + 1) + end($A) * (1 - 2 / ($period + 1));
            }
        }
        return array_slice(array_reverse($A), 0, $bars_count);
    }


    /**
     * Расчет точек для отриоовки горизонтальных ценовых линий
     * @param ChartLayout $chartLayout
     */
    public function calculateLines(ChartLayout $chartLayout)
    {
        $days = [];
        $key = 0;
        /** @var \DateTime $dateTime */
        foreach ($this->time as $dateTime) {

            // группировка данных по дням для формирования точек линий (open,high,low,close)
            $days[$dateTime->format('Ymd')]['datakey'][] = $key;
            $days[$dateTime->format('Ymd')]['o'][] = $this->open[$key];
            $days[$dateTime->format('Ymd')]['h'][] = $this->high[$key];
            $days[$dateTime->format('Ymd')]['l'][] = $this->low[$key];
            $days[$dateTime->format('Ymd')]['c'][] = $this->close[$key];


            $key++;
        }
        //перебор дней со своими значениями для формирования масива точек линий
        if ($chartLayout->getTimeFrame() != 'd' && $chartLayout->getTimeFrame() != 'w') {

            $key = 0;
            $lines = [];
            foreach ($days as $date => $val) {

                $keys = [ //ключи нужных значений точек в текущем дне
                    'h' => array_keys($val['h'], max($val['h']))[0],
                    'l' => array_keys($val['l'], min($val['l']))[0],
                    'o' => count($val['o']) - 1,
                    'c' => 0
                ];

                //массив точек начала линий со значением цены (x - номер бара по счету с начала)
                if ($chartLayout->getLinesHigh()) {
                    $lines[] = ['type' => 'hi', 'price' => $val['h'][$keys['h']], 'x' => $val['datakey'][$keys['h']]];
                }

                if ($chartLayout->getLinesLow()) {
                    $lines[] = ['type' => 'lo', 'price' => $val['l'][$keys['l']], 'x' => $val['datakey'][$keys['l']]];
                }

                if ($chartLayout->getLinesOpen()) {
                    $lines[] = ['type' => 'op', 'price' => $val['o'][$keys['o']], 'x' => $val['datakey'][$keys['o']]];
                }

                if ($chartLayout->getLinesClose()) {
                    $lines[] = ['type' => 'cl', 'price' => $val['c'][$keys['c']], 'x' => $val['datakey'][$keys['c']]];
                }

                $key++;
                if ($key == $chartLayout->getLinesDays()) {
                    break;
                }
            }
            $this->horizontalLines = $lines;
        }
    }



}
