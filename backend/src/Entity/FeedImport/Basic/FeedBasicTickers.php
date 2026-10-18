<?php

namespace App\Entity\FeedImport\Basic;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainTickerTrait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * Tickers
 *
 * @ORM\Table(name="feed_basic_tickers")
 * @ORM\Entity()
 */
class FeedBasicTickers
{
    use TickerIdTrait;
    use MainTickerTrait;
}

