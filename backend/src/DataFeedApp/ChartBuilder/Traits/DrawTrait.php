<?php


namespace App\DataFeedApp\ChartBuilder\Traits;


use App\DataFeedApp\Bar\Model\BarData;
use App\DataFeedApp\Bar\TimeFrame\Bar5MinTimeFrame;
use App\DataFeedApp\Bar\TimeFrame\BarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\MinuteBarTimeFrameInterface;
use App\Entity\Chart\ChartLayout;

trait DrawTrait
{
    protected $regularFont = __DIR__.'/../../../../public/fonts/open-sans/OpenSans-Regular.ttf';

    protected $boldFont = __DIR__.'/../../../../public/fonts/open-sans/OpenSans-Bold.ttf';
    /**
     * @var \Intervention\Image\Image
     */
    protected $img;

    /**
     * @var BarData
     */
    protected $feed;
    /**
     * @var ChartLayout
     */
    protected $chartLayout;

    /**
     * @var BarTimeFrameInterface
     */
    protected $timeFrame;


    /** Ценовой 5-угольник на оси Х
     * @param $price
     * @param $text
     * @param $setColorCollback
     */
    protected function drawPolygon($price, $text, $setColorCollback)
    {
        $price_y = $this->getY($price,$this->chartLayout, $this->feed);
        $points = array(
            $this->chartLayout->getAreaWidth(),
            $price_y,  // Point 1 (x, y)
            $this->chartLayout->getAreaWidth() + 8,
            $price_y + 6, // Point 2 (x, y)
            $this->chartLayout->getWidth(),
            $price_y + 6,  // Point 3 (x, y)
            $this->chartLayout->getWidth(),
            $price_y - 5,  // Point 4 (x, y)
            $this->chartLayout->getAreaWidth() + 8,
            $price_y - 5
        );

        $this->img->rectangle(
            $this->chartLayout->getAreaWidth(),
            $price_y - 8,
            $this->chartLayout->getWidth(),
            $price_y + 8,
            $setColorCollback
        );


        $this->img->text($text, $this->chartLayout->getAreaWidth() + 8, $price_y,
            function ($font) {
                $font->color('#fff');
                $font->file($this->regularFont);
                $font->align('left');
                $font->valign('center');
            }
        );
    }

    public function getY($price, ChartLayout $chartLayout, BarData $barData)
    {
        return ceil(($chartLayout->getPriceAreaHeight()) * ($barData->maxPrice - 1 * $price) / $barData->priceRange);
    }

    public function getX($barNumber, ChartLayout $chartLayout)
    {
        return $chartLayout->getX0() - $barNumber * $chartLayout->getBarWidth();
    }


    /** for drawGrid only
     * @param $range
     * @param $step
     * @param $chartLayout
     */
    protected  function drawLinesByStep($range, $step,$chartLayout) {
        if ($range <= 0.2) {
            $nextline = round($this->feed->minPrice, 1);
        } else {
            if (1 * $this->feed->minPrice >= 800) {
                $nextline = ceil($this->feed->minPrice / 10) * 10;
            } else {
                $nextline = ceil($this->feed->minPrice);
            }
        }

        // рисуем пунктирную линию через каждые $step выше целого числа от минимума
        for ($l = 0; $l <= 30; $l++) {
            $y0 = ceil(($chartLayout->getPriceAreaHeight()) * ($this->feed->maxPrice - 1 * $nextline) / $range);

            if ($y0 < $chartLayout->getAreaHeight() - $chartLayout->getVolumeAreaTrueHeight() && $y0 > 5) {

                $this->img->line(0, $y0, $chartLayout->getAreaWidth(), $y0, function ($font) {
                    $font->color($this->chartLayout->getGridColor());
                });

                if ($y0< 20)  return;

                $this->img->text($nextline, $this->chartLayout->getAreaWidth() + 8, $y0,
                    function ($font) {
                        $font->color($this->chartLayout->getGridTextColor());
                        $font->file($this->regularFont);
                        $font->align('left');
                        $font->valign('center');
                    }
                );
            }
            $nextline = $nextline + $step;
        }
    }



    /**
     * Рисуем ценовую сетку и шкалу цены
     */
    protected function drawGrid(ChartLayout $chartLayout)
    {
        //------ шаг сетки в доларах
        $range = $this->feed->priceRange;
        if ($range <= 0.05) {
            $step = 0.005;
        } elseif ($range <= 0.5) {
            $step = 0.05;
        } elseif ($range <= 1.5) {
            $step = 0.1;
        } elseif ($range <= 2.5) {
            $step = 0.25;
        } elseif ($range <= 3) {
            $step = 0.25;
        } elseif ($range <= 5) {
            $step = 0.5;
        } elseif ($range <= 15) {
            $step = 1;
        } elseif ($range <= 25) {
            $step = 2;
        } elseif ($range <= 35) {
            $step = 3;
        } elseif ($range <= 70) {
            $step = 5;
        } elseif ($range <= 150) {
            $step = 10;
        } elseif ($range <= 200) {
            $step = 15;
        } elseif ($range <= 250) {
            $step = 20;
        } elseif ($range <= 350) {
            $step = 50;
        } elseif ($range <= 550) {
            $step = 50;
        } else {
            $step = round(round($range, 1) / 10, 0);
        }

        $this->drawLinesByStep($range, $step,$chartLayout);
        $this->drawLinesByStep($range, $step*-1,$chartLayout);


        $this->img->text($this->feed->source, 3, $chartLayout->getHeight() - 1, function ($font) {
            $font->color($this->chartLayout->getGridTextColor());
            $font->size(12);
            $font->file($this->regularFont);
        });
    }


    /**
     * Рисуем оси У и Х и шкалу объема
     */
    protected function addAxis(ChartLayout $chartLayout)
    {
        $this->img->line(0, 0, $chartLayout->getAreaWidth(), 0, function ($font) {
            $font->color($this->chartLayout->getGridColor());
        });  // ось Х вверху

        $this->img->line(
            1,
            $chartLayout->getAreaHeight() ,
            $chartLayout->getAreaWidth(),
            $chartLayout->getAreaHeight() ,
            function ($font) {
            $font->color($this->chartLayout->getGridColor());
        }); // ось Х


        $this->img->line(
            $chartLayout->getAreaWidth(),
            0,
            $chartLayout->getAreaWidth(),
            $chartLayout->getAreaHeight(),
            function ($font) {
            $font->color($this->chartLayout->getGridColor());
        }
        ); // ценовая ось

        $this->img->line(
            0,
            0,
            0,
            $chartLayout->getAreaHeight(),
            function ($font) {
            $font->color($this->chartLayout->getGridColor());
        });// ценовая ось слева


        if ($chartLayout->isSeparateVolumeArea()) {

            $this->img->line(
                1,
                $chartLayout->getAreaHeight() - $chartLayout->getVolumeAreaHeight() - 26 - ($chartLayout->getBottomPadding() - 20),
                $chartLayout->getAreaWidth(),
                $chartLayout->getAreaHeight() - $chartLayout->getVolumeAreaHeight() - 26 - ($chartLayout->getBottomPadding() - 20),
                function ($font) {
            $font->color($this->chartLayout->getGridColor());
        });

        }

        // draw volume
        $volume1 = $chartLayout->getAreaHeight() - ceil($chartLayout->getVolumeAreaHeight() / 2);
        $volume2 = $chartLayout->getAreaHeight() - $chartLayout->getVolumeAreaHeight(); //

        $this->img->line(
            1,
            $volume1,
            3,
            $volume1,
            function ($font) {
            $font->color($this->chartLayout->getGridColor());
        });

        $this->img->line(
            1,
            $volume2,
            3,
            $volume2,
            function ($font) {
            $font->color($this->chartLayout->getGridColor());
        });

        if ($this->feed->maxVolume >= 1000000) {
            $stepvol = $this->feed->maxVolume / 1000;
            $km = 'm';
        } else {
            $km = 'k';
            $stepvol = round($this->feed->maxVolume / 3 / 100000, 1) * 100000;
        }

        $this->img->text(round($stepvol / 2000) . $km, 5, $volume1 - 7, function ($font) {
            $font->color($this->chartLayout->getGridColor());
            $font->file($this->regularFont);
        });

        $this->img->text(round($stepvol / 1000) . $km, 5, $volume2 - 7, function ($font) {
            $font->color($this->chartLayout->getGridColor());
            $font->file($this->regularFont);
        });

        $timeLabel = '';
        if(isset($this->feed->time[0])) {
            /** @var \DateTime $date */
            $date = $this->feed->time[0];
            $timeLabel =  $date->format('H:i');
        }

        //надпись таймфрейма
        $this->img->text(
            $this->timeFrame->label() ,
            $this->chartLayout->getWidth()-50, 7, function ($font) {
            $font->color($this->chartLayout->getGridTextColor());
            $font->file($this->regularFont);
                $font->size(14);
                $font->align('left');
                $font->valign('center');
        });

        $this->img->text(
            $timeLabel ,
            $this->chartLayout->getWidth()-27, 8, function ($font) {
            $font->color($this->chartLayout->getGridTextColor());
            $font->file($this->regularFont);
            $font->size(10);
            $font->align('left');
            $font->valign('center');
        });

    }

    /**
     * @param ChartLayout $chartLayout
     * @param BarData $dataFeed
     * @param $barNumber
     */
    protected function drawBar(
        ChartLayout $chartLayout,
        BarData $dataFeed,
        $barNumber
    ): void
    {
        $x = $this->getX($barNumber, $chartLayout);
        //расчет координат бара(свечки) на оси Y
        $hight = $this->getY($dataFeed->high[$barNumber],$chartLayout,$dataFeed);
        $close = $this->getY($dataFeed->close[$barNumber],$chartLayout,$dataFeed);
        $open = $this->getY($dataFeed->open[$barNumber],$chartLayout,$dataFeed);
        $low = $this->getY($dataFeed->low[$barNumber],$chartLayout,$dataFeed);
        $topleft = min($open, $close);
        $butright = max($open, $close);

        if( $chartLayout->getBarType() === 'line' && $barNumber >0 && $dataFeed->close[$barNumber-1]) {
            $close0 =  $this->getY($dataFeed->close[$barNumber-1],$chartLayout,$dataFeed);
            $x0 = $this->getX($barNumber-1, $chartLayout);
            $this->img->line($x, $close, $x0 , $close0,
                function ($draw) use ($chartLayout) {
                    $draw->color($chartLayout->getSpyColor());
                    $draw->width(1);
                });
            return;
        }


        if ($open >= $close) {

            $chartLayout->currentCandleColor = $chartLayout->getColorUp();
            $chartLayout->currentOutlineColor = $chartLayout->getOutlineColorUp();

        } else {

            $chartLayout->currentCandleColor = $chartLayout->getColorDown();
            $chartLayout->currentOutlineColor = $chartLayout->getOutlineColorDown();
        }

        if ($chartLayout->getBarType() === 'candle' || $chartLayout->getBarType() === 'bar') {
            //  рисуем стержень свечки
            $this->img->line($x, $low, $x, $hight,
                function ($draw) use ($chartLayout) {
                    $draw->color($chartLayout->currentOutlineColor);
                    $draw->width($chartLayout->getBarThick());
                });
        }


        if ($chartLayout->getBarType() == 'candle') {
            if ($butright - $topleft >= 1) {

                $this->img->rectangle(
                    $x - $chartLayout->getOutline(),
                    $topleft,
                    $x + $chartLayout->getOutline(),
                    $butright, function ($draw) use ($chartLayout) {
                    $draw->background($chartLayout->currentOutlineColor);
                });
            } // рисуем контур тела свечи
            else {
                if ($butright - $topleft < 1) {
                    $this->img->rectangle(
                        $x - $chartLayout->getOutline(),
                        $topleft - 1,
                        $x + $chartLayout->getOutline() ,
                        $butright + 1, function ($draw) use ($chartLayout) {
                        $draw->background($chartLayout->currentOutlineColor);
                    });
                }
            }

            if ($butright - $topleft > 1) {
                $this->img->rectangle(
                    $x - ($chartLayout->getOutline() - 1),
                    $topleft + 1,
                    $x + ($chartLayout->getOutline() - 1) ,
                    $butright - 1, function ($draw) use ($chartLayout) {
                    $draw->background($chartLayout->currentCandleColor);
                });
                if($chartLayout->getOutline() === 1) {
                    $this->img->line($x , $topleft + 1,$x, $butright - 1, function ($draw)  use ($chartLayout) {
                        $draw->color($chartLayout->currentCandleColor);
                    });
                }


            } // рисуем тело свечи
            if ($butright - $topleft < 1) {

                $this->img->rectangle(
                    $x - ($chartLayout->getOutline() - 1),
                    $topleft,
                    $x + ($chartLayout->getOutline() - 1) ,
                    $butright, function ($draw) use ($chartLayout) {
                    $draw->background($chartLayout->currentCandleColor);
                });

                if($chartLayout->getOutline() === 1) {
                    $this->img->line($x , $topleft ,$x, $butright, function ($draw)  use ($chartLayout) {
                        $draw->color($chartLayout->currentCandleColor);
                    });
                }

            }
        }// Закрашенный прямоугольник


        if ($chartLayout->getBarType() == 'bar') {
            if ($open >= $close) {

                $this->img->line($x  - 2, $open, $x, $open, function ($draw)  use ($chartLayout) {
                    $draw->color($chartLayout->getOutlineColorUp());
                });
                $this->img->line($x, $close, $x + 2, $close, function ($draw)  use ($chartLayout) {
                    $draw->color($chartLayout->getOutlineColorUp());
                });

                if ($chartLayout->getBarThick() >= 1) {
                    $this->img->line($x  - 2, $open + 1, $x, $open + 1, function ($draw)  use ($chartLayout) {
                        $draw->color($chartLayout->getOutlineColorUp());
                    });
                    $this->img->line($x, $close + 1, $x + 2, $close + 1, function ($draw)  use ($chartLayout) {
                        $draw->color($chartLayout->getOutlineColorUp());
                    });
                }
            } else {
                $this->img->line($x  - 2, $open, $x, $open, function ($draw) use ($chartLayout) {
                    $draw->color($chartLayout->getOutlineColorDown());
                });
                $this->img->line($x, $close, $x + 2, $close, function ($draw) use ($chartLayout) {
                    $draw->color($chartLayout->getOutlineColorDown());
                });

                if ($chartLayout->getBarThick() >= 1) {
                    $this->img->line($x - 2, $open - 1, $x, $open - 1, function ($draw) use ($chartLayout) {
                        $draw->color($chartLayout->getOutlineColorDown());
                    });
                    $this->img->line($x, $close - 1, $x + 2, $close - 1, function ($draw)  use ($chartLayout) {
                        $draw->color($chartLayout->getOutlineColorDown());
                    });
                }
            }
        }
    }

    /** Отрисовка ценовой линии
     * @param $price
     * @param $x
     * @param $setColorCallback
     */
    protected function drawLines($price, $x, $setColorCallback)
    {
        $y_price = $this->getY($price,$this->chartLayout, $this->feed);
        if ($y_price < $this->chartLayout->getAreaHeight() && $y_price > 5) {

            $this->img->line(
                $this->chartLayout->getAreaHeight() - $x * $this->chartLayout->getBarWidth() - $this->chartLayout->getOutline() - 2,
                $y_price,
                $this->chartLayout->getAreaWidth(), $y_price, $setColorCallback);
        }
    }


    /** Отрисовка  подписей оси Х
     * @param $x
     * @param $text
     */
    protected function drawXTimeLabel($x, \DateTime $barDateTime)
    {
        $text =  $barDateTime->format($this->timeFrame->timeLabelFormat());
        $this->img->line($x, 1, $x, $this->chartLayout->getAreaHeight()+5, function ($font) {
            $font->color($this->chartLayout->getGridColor());
        });

        if($this->timeFrame->minuteDuration() < 15 && $barDateTime->format('Hi') == '1000' ) return;

        $this->drawXBaseLabel($x,$text);
    }

    /** Отрисовка  подписей оси Х
     * @param $x
     * @param $text
     */
    protected function drawXDayLabel($x, \DateTime $barDateTime)
    {
        $text =  $barDateTime->format($this->timeFrame->dayLabelFormat());
        $this->img->line($x, 1, $x, $this->chartLayout->getAreaHeight()+5, function ($font) {
            $font->color($this->chartLayout->getGridColor());
        });

        // разделение дней пожирнеее линию
        if($this->timeFrame instanceof Bar5MinTimeFrame) {
            $this->img->line($x+1, 1, $x+1, $this->chartLayout->getAreaHeight()+5, function ($font) {
                $font->color($this->chartLayout->getGridColor());
            });
            $this->img->line($x-1, 1, $x-1, $this->chartLayout->getAreaHeight()+5, function ($font) {
                $font->color($this->chartLayout->getGridColor());
            });

        }
        $this->drawXBaseLabel($x,$text);
    }

    protected function drawXBaseLabel($x, $text)
    {
        if ($x < 40) return;

        $this->img->text($text, $x + 4, $this->chartLayout->getHeight() - 5, function ($font) {
            $font->color($this->chartLayout->getGridTextColor());
            $font->file($this->regularFont);
            $font->align('center');
            $font->valign('center');
        });
    }



    /** Отрисовка одного бара объема
     * @param $vol
     * @param $x
     * @param $setColorCallback
     */
    protected function drawVolume($vol, $x, $setColorCallback)
    {
        $volume = ($this->chartLayout->getAreaHeight()) - ceil($vol / ($this->feed->maxVolume / $this->chartLayout->getVolumeAreaHeight()));
        $n = max ($this->chartLayout->getBarWidth(),4);
        $this->img->rectangle(
            $x - floor(  $n  / 2)+1,
            $volume,
            $x +  floor(  $n   / 2)-1,
            $this->chartLayout->getAreaHeight(),
            $setColorCallback
        );
    }

    public function drawCompareBars(ChartLayout $chartLayout, BarData $barData)
    {
        foreach ($barData->time as $barNumber => $barDateTime) {
            $this->drawBar($chartLayout, $barData, $barNumber);
        }

    }

    /**
     * Отрисовка данных по оси Х - барыб, объем, подписи
     * @param ChartLayout $chartLayout
     */
    public function addChartData(ChartLayout $chartLayout)
    {
        /**
         * @var  $barNumber
         * @var \DateTime $barDateTime
         */
        foreach ($this->feed->time as $barNumber => $barDateTime) {
            $x = $this->getX($barNumber,$chartLayout);

            if ($chartLayout->getTimeFrame() != 'd' && $chartLayout->getTimeFrame() != 'w' && $chartLayout->getPreMarket() == 1) {

                if ($barDateTime->format('Hi') < 930 || $barDateTime->format('Hi') >= 1600) {

                    $this->img->rectangle(
                        $x - (ceil($chartLayout->getBarWidth() / 2) - 1),
                        1,
                        $x + ($chartLayout->getBarWidth() - ceil($chartLayout->getBarWidth() / 2)),
                        $chartLayout->getAreaHeight(),
                        function ($draw) {
                            $draw->background($this->chartLayout->getPreMarketColor());
                        }
                    );
                }
            }

            $drawXTimeLabel1 = false;
            if(
                $this->timeFrame instanceof MinuteBarTimeFrameInterface &&
                $this->timeFrame->daysRange() < 15 &&
                isset($this->feed->time[$barNumber + 1]) &&
                $barDateTime->format('d') != $this->feed->time[$barNumber + 1]->format('d')
            ) {
                $this->drawXDayLabel($x, $barDateTime);
                $drawXTimeLabel1 = true;
            }

            if(
                isset($this->feed->time[$barNumber + 1]) &&
                $barDateTime->format($this->timeFrame->divideConditionFormat()) != $this->feed->time[$barNumber + 1]->format($this->timeFrame->divideConditionFormat()) &&
                $drawXTimeLabel1 === false
            ) {
                $this->drawXTimeLabel($x, $barDateTime);
            }


            if ($this->feed->close[$barNumber] >= $this->feed->open[$barNumber]) {
                $this->drawVolume($this->feed->volume[$barNumber], $x,
                    function ($draw) {
                        $draw->background($this->chartLayout->getVolumeColorUp());
                    }
                );
            } else {
                $this->drawVolume($this->feed->volume[$barNumber], $x,
                    function ($draw) {
                        $draw->background($this->chartLayout->getVolumeColorDown());
                    });
            }

            $this->drawBar($chartLayout, $this->feed, $barNumber);
        }

        if ($this->feed->close[0] >= $this->feed->open[0]) {
            $this->drawPolygon($this->feed->close[0], round($this->feed->close[0], 2),
                function ($draw) {
                    $draw->background($this->chartLayout->getColorUp());
                }
            );
        } else {
            $this->drawPolygon($this->feed->close[0], round($this->feed->close[0], 2),
                function ($draw) {
                    $draw->background($this->chartLayout->getColorDown());
                }
            );
        } // Рисуем Цену на графике

    }


    /**
     * рисуем скользящие средние EMA SMA
     */
    protected function addMA()
    {
        //перебор подготовленых данных и отрисовка каждой скользящей средней
        $y_sma_name = 15;
        foreach ($this->feed->movingAverageData as $ma) {

            $count = count($ma['data']) - 1;
            $x = $this->chartLayout->getAreaWidth();
            for ($n = 0; $n < $count; $n++) {

                $this->drawMA($ma['data'][$n + 1], $ma['data'][$n], $x,function ($draw) use ($ma) {
                    $draw->color($ma['color']);
                });
                $x = $x - $this->chartLayout->getBarWidth();
            }

            $y_sma_name = $y_sma_name + 12;

        }
    }

    /** Отрисовка Скользящих средних по цене
     * @param $price
     * @param $price2
     * @param $x
     * @param $color
     */
    protected function drawMA($price, $price2, $x, $color)
    {
        $y_sma = $this->getY( $price,$this->chartLayout,$this->feed);
        $y_sma2 = $this->getY($price2,$this->chartLayout,$this->feed);

        if ($y_sma < $this->chartLayout->getAreaHeight() && $y_sma > 5) {

            $this->img->line(
                $x - $this->chartLayout->getBarWidth()+1, $y_sma, $x, $y_sma2,$color
            );
        }
    }

    /**
     * рисуем горизонтальные ценовые уровни (op,hi,lo,close, last price)
     */
    protected function addLines()
    {
        foreach ($this->feed->horizontalLines as $lines) {
            $this->drawLines($lines['price'], $lines['x'], function ($draw){
                $draw->color('rgba(255,0,0,.5)');
                }
            );
        }

        if ($this->chartLayout->getLineLastPrice()) {
            $y_price = $this->getY($this->feed->close[0],$this->chartLayout,$this->feed);
            if ($this->feed->close[0] >= $this->feed->open[0]) {
                $cc = function ($draw){ $draw->color($this->chartLayout->getColorUp());};
            } else {
                $cc = function ($draw){ $draw->color($this->chartLayout->getColorDown());};
            }
            $this->img->line(0, $y_price, $this->chartLayout->getAreaWidth(), $y_price, $cc);
        }
    }

    public function isMoreDark()
    {
        $this->img;
    }
}
