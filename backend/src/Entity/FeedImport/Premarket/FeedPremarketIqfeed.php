<?php

namespace App\Entity\FeedImport\Premarket;

use App\Entity\Feed\Traits\MainPremarketTrait;
use App\Entity\Feed\Traits\TickerIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Iqfeed
 *
 * @ORM\Table(name="feed_premarket_iqfeed")
 * @ORM\Entity()
 */
class FeedPremarketIqfeed
{
    use TickerIdTrait;
    use MainPremarketTrait;
}

