<?php

namespace App\Entity\Feed;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainTickerTrait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * Tickers
 *
 * @ORM\Table(name="feed_main_tickers")
 * @ORM\Entity()
 */
class MainTickers
{
    use TickerIdTrait;
    use MainTickerTrait;
}

