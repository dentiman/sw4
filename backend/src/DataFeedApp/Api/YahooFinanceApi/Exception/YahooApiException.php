<?php

namespace App\DataFeedApp\Api\YahooFinanceApi\Exception;

class YahooApiException extends \Exception
{
    const INVALID_RESPONSE = 1;
    const INVALID_VALUE = 2;
    const MISSING_CRUMB = 3;
}
