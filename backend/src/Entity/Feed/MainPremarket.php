<?php

namespace App\Entity\Feed;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainPremarketTrait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * MainPremarket
 *
 * @ORM\Table(name="feed_main_premarket")
 * @ORM\Entity
 */
class MainPremarket
{
    use TickerIdTrait;
    use MainPremarketTrait;
}

