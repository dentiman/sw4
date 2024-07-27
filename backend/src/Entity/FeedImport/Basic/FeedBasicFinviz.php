<?php

namespace App\Entity\FeedImport\Basic;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainFinvizDataTrait;
use App\Entity\Feed\Traits\TickerIdTrait;

/**
 * Finviz
 *
 * @ORM\Table(name="feed_basic_finviz")
 * @ORM\Entity()
 *
 */
class FeedBasicFinviz
{
    use TickerIdTrait;
    use MainFinvizDataTrait;
}

