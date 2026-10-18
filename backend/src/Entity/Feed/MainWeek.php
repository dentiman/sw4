<?php

namespace App\Entity\Feed;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainWeekTrait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * MainWeek
 *
 * @ORM\Table(name="feed_main_week")
 * @ORM\Entity
 */
class MainWeek
{
    use TickerIdTrait;
    use MainWeekTrait;
}

