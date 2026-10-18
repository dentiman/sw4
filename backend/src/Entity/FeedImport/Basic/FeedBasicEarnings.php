<?php

namespace App\Entity\FeedImport\Basic;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainEarningsTrait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * FeedBasicEarnings
 * @ORM\Entity
 * @ORM\Table(name="feed_basic_earnings")
 */
class FeedBasicEarnings
{
    use TickerIdTrait;
    use MainEarningsTrait;
}

