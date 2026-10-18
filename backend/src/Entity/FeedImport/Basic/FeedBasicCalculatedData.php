<?php

namespace App\Entity\FeedImport\Basic;

use App\Entity\Feed\Traits\MainCalculatedDataTrait;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Feed\Traits\MainTechTrait;
use App\Entity\Feed\Traits\MainTmpTrait;
use App\Entity\Feed\Traits\MainEarningsTrait;
use App\Entity\Feed\Traits\MainLevel1Trait;
use App\Entity\Feed\Traits\MainMinutePrevTrait;
use App\Entity\Feed\Traits\MainPremarketTrait;
use App\Entity\Feed\Traits\MainTickerTrait;
use App\Entity\Feed\Traits\MainWeekTrait;
use App\Entity\Feed\Traits\TickerIdTrait;


/**
 * @ORM\Table(name="feed_basic_calculated_data")
 * @ORM\Entity()
 */
class FeedBasicCalculatedData {

    use TickerIdTrait;
    use MainCalculatedDataTrait;
}
