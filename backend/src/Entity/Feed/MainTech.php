<?php

namespace App\Entity\Feed;

use App\Entity\Feed\Traits\MainTechTrait;
use App\Entity\Feed\Traits\MainTmpTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Feed\Traits\MainFinvizDataTrait;
use App\Entity\Feed\Traits\MainTickerTrait;
use App\Entity\Feed\Traits\TickerIdTrait;


/**
 * MainBasicData
 * @ORM\Entity
 * @ORM\Table(name="feed_main_tech")
 *
 */
class MainTech
{
    use TickerIdTrait;
    use MainTechTrait;
}

