<?php

namespace App\DataFeedApp\Bar\Storage;


use App\DataFeedApp\Bar\Model\BarData;

class MinuteBarStorage
{
    const FOLDER =  __DIR__ . '/../../../../var/storage/intraday';

    /**
     * @param string $ticker
     * @return BarData
     * @throws \Exception
     */
    public static function getBars(string  $ticker) : BarData
    {
        if(file_exists( self::getFileName($ticker)) == false) throw new \Exception('Ticker '.$ticker.' not exist in local storage');
        return new BarData(json_decode(file_get_contents(self::getFileName($ticker)),true));
    }


    public static function saveBars(string $ticker, BarData $barData)
    {
        file_put_contents(self::getFileName($ticker),json_encode($barData->toArray()));
    }


    public static function deleteBars(string $ticker)
    {
       if(file_exists( self::getFileName($ticker))) {
            unlink(self::getFileName(($ticker)));
       };
    }

    protected static function getFileName(string $ticker)
    {
        return self::FOLDER.'/'.$ticker.'.txt';
    }
}
