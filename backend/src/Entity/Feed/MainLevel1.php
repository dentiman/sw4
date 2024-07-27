<?php

namespace App\Entity\Feed;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainLevel1Trait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * MainLevel1
 * @ORM\Entity
 * @ORM\Table(name="feed_main_level1")
 */
class MainLevel1
{
    use TickerIdTrait;
    use MainLevel1Trait;
}

