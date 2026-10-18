<?php

namespace App\DataFeedApp\Api\YahooFinanceApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class YahooApiClientFactory
{
    /**
     * @param ClientInterface|null $guzzleClient
     *
     * @return YahooApiClient
     */
    public static function createApiClient(ClientInterface $guzzleClient = null)
    {
        $guzzleClient = $guzzleClient ? $guzzleClient : new Client();
        $resultDecoder = new ResultDecoder();

        return new YahooApiClient($guzzleClient, $resultDecoder);
    }
}
