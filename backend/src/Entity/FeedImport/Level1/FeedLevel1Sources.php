<?php

namespace App\Entity\FeedImport\Level1;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainLevel1Trait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * Sources
 *
 * @ORM\Table(name="feed_level1_sources")
 * @ORM\Entity()
 */
class FeedLevel1Sources
{
    use TickerIdTrait;
    use MainLevel1Trait;
}

