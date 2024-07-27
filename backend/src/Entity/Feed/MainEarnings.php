<?php

namespace App\Entity\Feed;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainEarningsTrait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * MainEarnings
 * @ORM\Entity
 * @ORM\Table(name="feed_main_earnings")
 */
class MainEarnings
{
    use TickerIdTrait;
    use MainEarningsTrait;
}

