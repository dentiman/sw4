<?php

namespace App\Entity\Feed;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainMinutePrevTrait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * MainMinutePrev
 *
 * @ORM\Table(name="feed_main_minute_prev")
 * @ORM\Entity
 */
class MainMinutePrev
{
    use TickerIdTrait;
    use MainMinutePrevTrait;
}

