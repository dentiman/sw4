<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.01.17
 * Time: 16:24
 */

namespace App\DataFeedApp;


class Helper
{


    const MARKET_CLOSED_TIME = 0;  //20:00 - 04:00
    const PRE_MARKET_TIME = 3; // 4:00 - 09:30
    const MARKET_OPEN_TIME = 1; //9:30 - 16:00
    const POST_MARKET_TIME = 2; //16:00 - 20:00



    const SECTORS = [
        "1"=>"Basic Materials",
        "2"=>"Conglomerates",
        "3"=>"Consumer Goods",
        "4"=>"Financial",
        "5"=>"Healthcare",
        "6"=>"Industrial Goods",
        "7"=>"Services",
        "8"=>"Technology",
        "9"=>"Utilities"
    ];

    const EXCHANGES = [ 1=> 'NYSE', 2 => 'NASDAQ', 3=>  'AMEX', 4=> 'NYSEARCA', 5=> 'other', 6=> 'BATS'];

    const HOLIDAYS = [
        "20210118", //Martin Luther King Jr. Day
        "20210215",//Washington's Birthday
        "20210402",//Good Friday
        "20210531",//Memorial Day
        "20210705",//Independence Day
        "20210906",//Labor Day
        "20211125",//Thanksgiving Day
//        "20211126",//Day after Thanksgiving Day !! OPEN TIL 1:00 PM ET
        "20211224",//Christmas Day
        "20220101",//New Year's Day
    ];


    public static function marketTime()
    {
        if (in_array(date('Ymd'), self::HOLIDAYS)) return 0;

        $day_of_week = date('w', time());
        if($day_of_week == 0 || $day_of_week == 6) return 0; //субота или воскресение

        $time = date('Hi') * 1;
        if ($time >= 930 && $time < 1600) {
            return self::MARKET_OPEN_TIME;
        } elseif ($time < 930 && $time >= 400) {
            return self::PRE_MARKET_TIME;
        } elseif ($time >= 1600 && $time < 2000) {
            return self::POST_MARKET_TIME;
        } else {
            return self::MARKET_CLOSED_TIME;
        }
    }

    public static function lastSessionDay(): \DateTimeInterface
    {
        for (
            $currentTime = new \Datetime('now', new \DateTimeZone('America/New_York'));
            $currentTime->diff(new \Datetime('now', new \DateTimeZone('America/New_York')))->format('%a')*1 < 5;
            $currentTime->modify("-1 day")

        ) {
            if(
                $currentTime->format('Ymd') === new \Datetime('now', new \DateTimeZone('America/New_York'))
                && in_array(self::marketTime(),[0,3])
                )  continue;
            if(in_array($currentTime->format('w'),[0,6])) continue;
            if(in_array($currentTime->format('Ymd'),self::HOLIDAYS)) continue;
            return $currentTime;
        }

    }

    public static function marketIsOpen()
    {
        return self::marketTime() === self::MARKET_OPEN_TIME;
    }

    public  function getExchanges()
    {
        return $this::EXCHANGES;
    }

    public static function getIndeces()
    {
        return [ 0 => '-', 1 => 'S&P 500', 2=>  'DJIA'];
    }


    public function getSectors()
    {
        return $this::SECTORS;
    }


    public function getExchenges()
    {
        return $this::EXCHANGES;
    }


    public function getCountries()
    {
        return [];
    }


    public function getSectorChoices()
    {
        $choices = [];
        foreach ($this::SECTORS as $key => $SECTOR) {

            $choices[$SECTOR] = $key;
        }

        return $choices;
    }

    public function getExchangeChoices()
    {
        $e = [];
        foreach ($this::EXCHANGES as $key => $EXCHANGE) {
            $e[$EXCHANGE] = $key;
        }
        return $e;
    }



}
