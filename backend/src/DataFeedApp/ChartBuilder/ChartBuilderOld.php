<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 21.12.16
 * Time: 20:40
 */

namespace App\DataFeedApp\ChartBuilder;

class ChartBuilderOld
{
    protected $img;
    protected $settings = [
        't' => 'SPY',
        'tf' => 'd',
        'w' => '1140',
        'aw' => '655',
        'h' => '400',
        'vol_wdt' => '40',
        'bgcolor' => 'rgb(255,255,255)',
        'setka' => 'rgb(153,153,153)', //цвет шкалы цены и подписей оси
        'grid_color' => 'rgba(227,227,227,50)',// цвет ценовой сетки
        'prem' => '0',
        'voll' => '0',
        'spy_on' =>
            [
                'check' => false,
                'color' => 'rgb(166,166,166)'
            ],
        'type' => 'candle',
        'barw' => 6,
        'thick' => '0',
        'kontur' => '1',
        'vol_b_w' => '4',
        'colorup' => 'rgb(0,242,10)',
        'colordown' => 'rgb(250,15,0)',
        'fcolorup' => 'rgb(0,0,0)',
        'fcolord' => 'rgb(0,0,0)',
        'vol_c_u' => 'rgb(0,212,85)',
        'vol_c_d' => 'rgb(242,15,0)',
        'sma1' => [
            'color' => 'rgb(181,181,181)',
            'choice' => false
        ],
        'sma2' =>
            [
                'color' => 'rgb(219,219,219)',
                'choice' => false
            ],
        'sma3' =>
            [
                'color' => 'rgb(199,199,199)',
                'choice' => false
            ],
        'ema1' =>
            [
                'color' => 'rgb(191,191,191)',
                'choice' => false
            ],
        'ema2' =>
            [
                'color' => 'rgb(191,191,191)',
                'choice' => false
            ],
        'ema3' =>
            [
                'color' => 'rgb(196,196,196)',
                'choice' => false
            ],
        'mav' => '0',
        'mat' => '1',
        'lines_op' =>
            [
                'check' => '1',
                'color' => 'rgb(214,22,22)'
            ],
        'lines_hi' =>
            [
                'check' => '1',
                'color' => 'rgb(173,20,20)'
            ],
        'lines_lo' =>
            [
                'check' => '1',
                'color' => 'rgb(209,124,25)'
            ],
        'lines_cl' =>
            [
                'check' => '1',
                'color' => 'rgb(148,47,25)'
            ],
        'lines_last' =>
            [
                'check' => '1',
                'color' => 'rgb(209,96,32)'
            ],
        'lines_d' => '1',
        'premarket_color' => 'rgba(99,99,99,95)',
        'feed' => false
    ];

    protected $colors = [
        'black_color' => 'rgb(0,0,0)',
        'green_color' => 'rgb(0,255,0)',
        'red_color' => 'rgb(255,0,0)',
    ];


    protected $first_label_text = array( // формы времни для нижней шкалы под графиком
        '1' => array('H', 'H:i'),
        '2' => array('H', 'H:i'),
        '3' => array('H', 'H:i'),
        '5' => array('d', 'D d M'),
        '15' => array('d', 'D d M'),
        '30' => array('d', 'd M'),
        '60' => array('W', 'd M'),
        'd' => array('M', 'M Y'),
        'w' => array('Y', 'Y')
    );

    protected $second_label_text = array(// формы времни для верхней шкалы под графиком
        '1' => array('H', 'H:i'),
        '2' => array('H', 'H:i'),
        '3' => array('H', 'H:i'),
        '5' => array('i', 'H:i', '00'),
        '15' => array('i', 'H', '00'),
        '30' => array('d', 'd M'),
        '60' => array('W', 'd M'),
        'd' => array('M', 'M Y'),
        'w' => array('W', '')
    );


    protected $right_padding = 50;//px

    protected $bottom_padding = 40;//px

    protected $volume_bars_height = 40;//px

    protected $volume_area_height = 0;//px

    protected $price_area_height;//px

    protected $x0;

    public $feed;

    protected $bars_count;

    protected $dotted_line_styles;

    public $labels;

    public $labels2;

    public $MA = [];

    public $lines = [];

    public $compare = false;

    public $test;

    public function __construct($settings)
    {

        $this->settings = array_merge($this->settings, $settings, $this->colors);

        $this->img = imagecreatetruecolor($this->settings['w'], $this->settings['h']);

        $this->reformatColors();

        $this->setCalculation();

        $this->setBackground();

        $this->feed = new ChartDataFeeder();



        $this->feed->loadData($this->settings['t'], $this->settings['tf'], $this->settings['prem']);

        $this->test = $this->settings;

        if ($this->feed->source == false) {
            $this->addNoData();
        } else {

//            if ($this->settings['spy_on']['check']) {
//
//                $this->compare = new ChartDataFeeder();
//
//                $this->compare->loadData('SPY', $this->settings['tf'], $this->settings['prem']);
//
//                $this->compare->calculate($this->bars_count);
//            }

//
            $this->calculateLines();

            $this->calculateMA();

            $this->feed->calculate($this->bars_count);
//
//
            $this->addGrid();

            $this->addAxis();
//
            $this->addLines();

            $this->addMA();

            $this->addChartData();
        }

    }

    /**
     * Преобразует цвета в массиве настроек в формат PHP GD
     */
    protected function reformatColors()
    {
        // reformat colors to img format
        $this->settings['grid_color'] = $this->TransparentColor($this->settings['setka']);

        foreach ($this->settings as $key => $value) {

            if (is_array($value)) {

                if (isset($value['color']) && strpos($value['color'], 'rgb') === 0) {

                    $this->settings[$key]['color'] = $this->getImageColor($value['color']);
                }

            } elseif (strpos($value, 'rgb') === 0) {
                $this->settings[$key] = $this->getImageColor($value);
            }
        }
    }


    /** Реформат цвета rgb в полупрозрачный int
     * @param $rgb
     * @return bool|int
     */
    public function TransparentColor($rgb)
    {

        $rgb = explode(',', str_replace(['rgb(', ')', 'rgba('], '', $rgb));

        if (count($rgb) == 3) {
            return imagecolorallocatealpha($this->img, $rgb[0], $rgb[1], $rgb[2], 50);
        }
        return false;
    }


    /**
     * @param $rgb
     * @return bool|int
     */
    public function getImageColor($rgb)
    {

        $rgb = explode(',', str_replace(['rgb(', ')', 'rgba('], '', $rgb));

        if (count($rgb) == 3) {
            return imagecolorallocate($this->img, $rgb[0], $rgb[1], $rgb[2]);
        } elseif (count($rgb) == 4) {
            return imagecolorallocatealpha($this->img, $rgb[0], $rgb[1], $rgb[2], $rgb[3]);
        }
        return false;
    }

    /**
     * расчет основных покзателей на основании входящих параметров $settings (настроек графика)
     */
    protected function setCalculation()
    {

        // separate volume area
        if ($this->settings['voll'] == '1') {
            $this->volume_area_height = $this->volume_bars_height;
        }

        //<editor-fold desc="+set price area height">
        if ($this->settings['h'] <= 200) {

            $this->price_area_height = $this->settings['h'] - (30 + $this->volume_area_height + ($this->bottom_padding - 20));
        } elseif ($this->settings['h'] > 200 && $this->settings['h'] < 300) {

            $this->price_area_height = $this->settings['h'] - (35 + $this->volume_area_height + ($this->bottom_padding - 20));
        } elseif ($this->settings['h'] >= 300 && $this->settings['h'] < 400) {

            $this->price_area_height = $this->settings['h'] - (40 + $this->volume_area_height + ($this->bottom_padding - 20));
        } else {
            $this->price_area_height = $this->settings['h'] - (50 + $this->volume_area_height + ($this->bottom_padding - 20));
        }
        //</editor-fold>

        $this->settings['aw'] = $this->settings['w'] * 1 - $this->right_padding;

        $this->settings['ah'] = $this->settings['h'] * 1 - $this->bottom_padding;

        $this->bars_count = ceil(($this->settings['aw']) / $this->settings['barw']);

        $this->x0 = $this->settings['w'] - ($this->right_padding + 5);

        $this->dotted_line_styles = [
            [
                $this->settings['setka'],
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT
            ],
            [
                $this->settings['setka'],
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT,
                IMG_COLOR_TRANSPARENT
            ]
        ];

        //Исправляем ошибочное значение
        if ($this->settings['kontur'] == 15) {
            $this->settings['kontur'] = 2;
        }

    }

    /**
     *фон графика
     */
    protected function setBackground()
    {

        $transparent = imagecolorallocate($this->img, 196, 196, 196);

        $trans = imagecolortransparent($this->img, $transparent);
        imagefill($this->img, 0, 0, $transparent);

        imagefilledrectangle(
            $this->img,
            1,
            1,
            $this->settings['aw'],
            $this->settings['ah'],
            $this->settings['bgcolor']
        );
    }

    /** Расчет значения на оси У для текущей цены $price
     * @param ChartDataFeeder $dataFeed
     * @param $price
     * @return float
     */
    protected function getY(ChartDataFeeder $dataFeed, $price)
    {
        return ceil(($this->price_area_height) * ($dataFeed->max_price - 1 * $price) / $dataFeed->price_range);
    }

    /**
     * Расчет значения на оси X для текущего бара $bar_number
     * @param $bar_number
     * @return float
     */
    protected function getX($bar_number)
    {
        return $this->x0 - $bar_number * $this->settings['barw'];
    }

    /** Ценовой 5-угольник на оси Х
     * @param $price
     * @param $text
     * @param $color
     */
    protected function drawPolygon($price, $text, $color)
    {
        $price_y = $this->getY($this->feed, $price);
        $pol_values = array(
            $this->settings['aw'],
            $price_y,  // Point 1 (x, y)
            $this->settings['aw'] + 8,
            $price_y + 6, // Point 2 (x, y)
            $this->settings['w'],
            $price_y + 6,  // Point 3 (x, y)
            $this->settings['w'],
            $price_y - 5,  // Point 4 (x, y)
            $this->settings['aw'] + 8,
            $price_y - 5
        );

        imagefilledpolygon($this->img, $pol_values, 5, $color);
        imagestring($this->img, 2, $this->settings['aw'] + 10, $price_y - 6, $text, $this->settings['black_color']);
    }


    /** Отрисовка Скользящих средних по цене
     * @param $price
     * @param $price2
     * @param $x
     * @param $color
     */
    protected function drawMA($price, $price2, $x, $color)
    {

        $y_sma = $this->getY($this->feed, $price);
        $y_sma2 = $this->getY($this->feed, $price2);

        if ($y_sma < $this->settings['ah'] && $y_sma > 5) {

            imageline($this->img, $x - $this->settings['barw'] + 1, $y_sma, $x, $y_sma2, $color);

            if ($this->settings['mat'] == 2) {
                imageline($this->img, $x - $this->settings['barw'] + 1, $y_sma + 1, $x, $y_sma2 + 1, $color);
            }
        }

    }

    /** Отрисовка одного бара объема
     * @param int $vol
     * @param int $x
     * @param int $color
     */
    protected function drawVolume($vol, $x, $color)
    {
        $volume = ($this->settings['ah']) - ceil($vol / ($this->feed->max_volume / $this->settings['vol_wdt']));

        imagefilledrectangle(
            $this->img, $x - ($this->settings['vol_b_w'] - 1) / 2,
            $volume, $x + ($this->settings['vol_b_w'] - 1) / 2,
            $this->settings['ah'],
            $color
        );
    }


    /**
     * Отрисовка данных по оси Х - барыб, объем, подписи
     */
    protected function addChartData()
    {
        foreach ($this->feed->t as $bar_number => $bar_time) {
            $x = $this->getX($bar_number);

            //<editor-fold desc="Фон премаркета">
            if ($this->settings['tf'] != 'd' && $this->settings['tf'] != 'w' && $this->settings['prem'] == 1) {

                if (date('Hi', $bar_time) < 930 || date('Hi', $bar_time) >= 1600) {

                    imagefilledrectangle($this->img, $x - (ceil($this->settings['barw'] / 2) - 1), 1,
                        $x + ($this->settings['barw'] - ceil($this->settings['barw'] / 2)), $this->settings['ah'],
                        $this->settings['premarket_color']);
                }
            }
            //</editor-fold>

            if ( isset($this->second_label_text[$this->settings['tf']][2]) &&
                date($this->second_label_text[$this->settings['tf']][0],
                    $bar_time) == $this->second_label_text[$this->settings['tf']][2] && date('H', $bar_time) != '16'
            ) {
                $this->drawSecondLabel($x, date($this->second_label_text[$this->settings['tf']][1], $bar_time));
            }



            if (
                isset($this->feed->t[$bar_number + 1]) &&
                date($this->first_label_text[$this->settings['tf']][0],
                    $this->feed->t[$bar_number + 1]) != date($this->first_label_text[$this->settings['tf']][0],
                    $bar_time)
            ) {
                $this->drawFirstLabel($x, date($this->first_label_text[$this->settings['tf']][1], $bar_time));
            }

            //<editor-fold desc="Отрисовка объема">
            if ($this->feed->c[$bar_number] >= $this->feed->o[$bar_number]) {
                $this->drawVolume($this->feed->v[$bar_number], $x, $this->settings['vol_c_u']);
            } else {
                $this->drawVolume($this->feed->v[$bar_number], $x, $this->settings['vol_c_d']);
            }
            //</editor-fold>


            if ($this->compare) {

                if( is_numeric($this->settings['tf'])) {
                    $compare_key = array_search($bar_time, $this->compare->t);
                    if ($compare_key) {
                        $this->drawBar($this->compare, $compare_key, true);
                    }
                } else {
                    $this->drawBar($this->compare, $bar_number, true);
                }

            }

            $this->drawBar($this->feed, $bar_number);
        }

        if ($this->feed->c[0] >= $this->feed->o[0]) {
            $this->drawPolygon($this->feed->c[0], round($this->feed->c[0], 2), $this->settings['green_color']);
        } else {
            $this->drawPolygon($this->feed->c[0], round($this->feed->c[0], 2), $this->settings['red_color']);
        } // Рисуем Цену на графике

    }

    /** Отрисовка свечи
     *
     */
    protected function drawBar($dataFeed, $bar_number, $compare = false)
    {
        $x = $this->getX($bar_number);
        //расчет координат бара(свечки) на оси Y
        $hight = $this->getY($dataFeed, $dataFeed->h[$bar_number]);
        $close = $this->getY($dataFeed, $dataFeed->c[$bar_number]);
        $open = $this->getY($dataFeed, $dataFeed->o[$bar_number]);
        $low = $this->getY($dataFeed, $dataFeed->l[$bar_number]);


        if ($this->settings['thick'] == 1 && $this->settings['kontur'] == 2) {
            $pixel = 1;
        } else {
            $pixel = 0;
        }

        if ($this->settings['barw'] >= 6) {
            $plus = 2;
        } else {
            $plus = 1;
        }


        $topleft = min($open, $close);
        $butright = max($open, $close);

        imagesetthickness($this->img, $this->settings['thick'] + 1);


        if ($open >= $close) {

            if ($compare) {
                $color = imagecolorallocate($this->img, 196, 196, 196);
                $ccolor = $this->settings['spy_on']['color'];
            } else {
                $color = $this->settings['colorup'];
                $ccolor = $this->settings['fcolorup'];
            }

        } else {

            if ($compare) {
                $color = $this->settings['spy_on']['color'];
                $ccolor = $this->settings['spy_on']['color'];
            } else {
                $color = $this->settings['colordown'];
                $ccolor = $this->settings['fcolord'];
            }
        }


        //  рисуем стержень свечки
        imageline($this->img, $x, $low, $x, $hight, $ccolor);
        imagesetthickness($this->img, 1);

        if ($this->settings['type'] == 'candle') {
            if ($butright - $topleft >= 1) {
                imagerectangle($this->img, $x - $this->settings['kontur'], $topleft,
                    $x + $this->settings['kontur'] - $pixel, $butright, $ccolor);
            } // рисуем контур тела свечи
            else {
                if ($butright - $topleft < 1) {
                    imagerectangle($this->img, $x - $this->settings['kontur'], $topleft - 1,
                        $x + $this->settings['kontur'] - $pixel, $butright + 1, $ccolor);
                }
            }

            if ($butright - $topleft > 1) {
                imagefilledrectangle($this->img, $x - ($this->settings['kontur'] - 1), $topleft + 1,
                    $x + ($this->settings['kontur'] - 1 - $pixel), $butright - 1, $color);
            } // рисуем тело свечи
            if ($butright - $topleft < 1) {
                imagefilledrectangle($this->img, $x - ($this->settings['kontur'] - 1), $topleft,
                    $x + ($this->settings['kontur'] - 1 - $pixel), $butright, $color);
            }
        }// Закрашенный прямоугольник


        if ($this->settings['type'] == 'bar') {
            if ($open >= $close) {
                imageline($this->img, $x - $plus - 2, $open, $x, $open, $this->settings['fcolorup']);
                imageline($this->img, $x, $close, $x + 2, $close, $this->settings['fcolorup']);
                if ($this->settings['thick'] >= 1) {
                    imageline($this->img, $x - $plus - 2, $open + 1, $x, $open + 1, $this->settings['fcolorup']);
                    imageline($this->img, $x, $close + 1, $x + 2, $close + 1, $this->settings['fcolorup']);
                }
            } else {
                imageline($this->img, $x - $plus - 2, $open, $x, $open, $this->settings['fcolord']);
                imageline($this->img, $x, $close, $x + 2, $close, $this->settings['fcolord']);
                if ($this->settings['thick'] >= 1) {
                    imageline($this->img, $x - $plus - 2, $open - 1, $x, $open - 1, $this->settings['fcolord']);
                    imageline($this->img, $x, $close - 1, $x + 2, $close - 1, $this->settings['fcolord']);
                }
            }
        }
    }

    /** Отрисовка ценовой линии
     * @param float $price
     * @param int $x
     * @param int $color
     */
    protected function drawLines($price, $x, $color)
    {
        $y_price = $this->getY($this->feed, $price);
        if ($y_price < $this->settings['ah'] && $y_price > 5) {
            imageline($this->img,
                ($this->settings['aw'] - $x * $this->settings['barw']) - $this->settings['kontur'] - 2, $y_price,
                $this->settings['aw'], $y_price, $color);
            //imagestring($img,1,$x-$this->settings['kontur']+1,$y_price-10,$price,$red );

        }
    }

    /**
     * Рисуем ценовую сетку и шкалу цены
     */
    protected function addGrid()
    {
        $style = Array(
            $this->settings['grid_color'],
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT
        );

        imagesetstyle($this->img, $style);

        $style2 = Array(
            $this->settings['grid_color'],
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT,
            IMG_COLOR_TRANSPARENT
        );

        //------ шаг сетки в доларах
        $range = $this->feed->price_range;
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


        $y0 = ceil(($this->price_area_height) * ($this->feed->max_price - 1 * ceil($this->feed->min_price)) / $range);// координата начала сетки - целое число от минимума

        if ($range <= 0.2) {
            $nextline = round($this->feed->min_price, 1);
        } else {
            if (1 * $this->feed->min_price >= 800) {
                $nextline = ceil($this->feed->min_price / 10) * 10;
            } else {
                $nextline = ceil($this->feed->min_price);
            }
        }

        // рисуем пунктирную линию через каждые $step выше целого числа от минимума
        for ($l = 0; $l <= 30; $l++) {
            $y0 = ceil(($this->price_area_height) * ($this->feed->max_price - 1 * $nextline) / $range);

            if ($y0 < $this->settings['ah'] - $this->volume_area_height && $y0 > 5) {

                imageline($this->img, 0, $y0, $this->settings['aw'], $y0, IMG_COLOR_STYLED);

                imagestring($this->img, 2, $this->settings['w'] - 37, $y0 - 6, $nextline, $this->settings['setka']);
            }

            $nextline = $nextline + $step;
        }


        if ($range <= 0.2) {
            $nextline = round($this->feed->min_price, 1);
        } else {
            if (1 * $this->feed->min_price >= 800) {
                $nextline = ceil($this->feed->min_price / 10) * 10;
            } else {
                $nextline = ceil($this->feed->min_price);
            }
        }

        $nextline = $nextline - $step;

        for ($l = 0; $l <= 30; $l++) {
            $y0 = ceil(($this->price_area_height) * ($this->feed->max_price - 1 * $nextline) / $range);
            if ($y0 < $this->settings['ah'] - $this->volume_area_height && $y0 > 5) {
                imageline($this->img, 0, $y0, $this->settings['aw'], $y0, IMG_COLOR_STYLED);

                imagestring($this->img, 2, $this->settings['w'] - 37, $y0 - 6, $nextline, $this->settings['setka']);
            }
            $nextline = $nextline - $step;
        } // рисуем линию через каждые $step ниже целого числа от минимума


        imagestring($this->img, 2, 5, $this->settings['h'] - 30, $this->feed->source, $this->settings['setka']);
    }

    /**
     * Рисуем оси У и Х и шкалу объема
     */
    protected function addAxis()
    {

        imageline($this->img, 0, 0, $this->settings['aw'], 0, IMG_COLOR_STYLED);  // ось Х вверху
        imageline($this->img, 1, $this->settings['ah'], $this->settings['aw'], $this->settings['ah'],
            IMG_COLOR_STYLED);  // ось Х


        if ($this->settings['voll'] == 1) {

            imageline($this->img, 1,
                $this->settings['h'] - $this->settings['vol_wdt'] - 26 - ($this->bottom_padding - 20),
                $this->settings['aw'],
                $this->settings['h'] - $this->settings['vol_wdt'] - 26 - ($this->bottom_padding - 20),
                $this->settings['setka']);

        }

        imageline($this->img, $this->settings['aw'], 0, $this->settings['aw'], $this->settings['ah'],
            IMG_COLOR_STYLED);// ценовая ось
        imageline($this->img, 0, 0, 0, $this->settings['ah'], IMG_COLOR_STYLED);// ценовая ось слева


        // draw volume

        $volume1 = ($this->settings['ah']) - ceil($this->settings['vol_wdt'] / 2);
        $volume2 = ($this->settings['ah']) - $this->settings['vol_wdt']; //

        imageline($this->img, 1, $volume1, 3, $volume1, $this->settings['setka']);
        imageline($this->img, 1, $volume2, 3, $volume2, $this->settings['setka']);

        if ($this->feed->max_volume >= 1000000) {
            $stepvol = $this->feed->max_volume / 1000;
            $km = 'm';
        } else {
            $km = 'k';
            $stepvol = round($this->feed->max_volume / 3 / 100000, 1) * 100000;
        }
        imagestring($this->img, 2, 5, $volume1 - 7, round($stepvol / 2000) . $km, $this->settings['setka']);
        imagestring($this->img, 2, 5, $volume2 - 7, round($stepvol / 1000) . $km, $this->settings['setka']);


        //надпись таймфрейма
        imagestring($this->img, 2, 20, 0, $this->settings['tf'] . '  ' . date('M d, H:i', strtotime($this->feed->t[0])),
            $this->settings['setka']);
    }


    /** Отрисовка нижней части подписей оси Х
     * @param $x
     * @param $text
     */
    protected function drawFirstLabel($x, $text)
    {
        imagesetstyle($this->img, $this->dotted_line_styles[0]);
        imageline($this->img, $x, 1, $x, $this->settings['h'] - 35, IMG_COLOR_STYLED);
        //imagettftext($img, $fsize,0,$x,$h-6,$grey, $font,$A['labels'][$n]);
        imagestring($this->img, 2, $x + 4, $this->settings['h'] - 16, $text, $this->settings['setka']);
    }

    /** Отрисовка верхней части подписей оси Х
     * @param $x
     * @param $text
     */
    protected function drawSecondLabel($x, $text)
    {
        imagesetstyle($this->img, $this->dotted_line_styles[1]);
        imagestring($this->img, 2, $x - (20 - $this->settings['tf']), $this->settings['h'] - 30, $text,
            $this->settings['setka']);
        imageline($this->img, $x, 1, $x, $this->settings['ah'], IMG_COLOR_STYLED);
    }


    /**
     * рисуем горизонтальные ценовые уровни (op,hi,lo,close, last price)
     */
    protected function addLines()
    {

        foreach ($this->lines as $lines) {
            $this->drawLines($lines['price'], $lines['x'], $this->settings['lines_' . $lines['type']]['color']);
        }

        if ($this->settings['tf'] == 'd' && $this->settings['lines_last']['check']) {
            $y_price = $this->getY($this->feed, $this->feed->c[0]);
            imageline($this->img, 0, $y_price, $this->settings['aw'], $y_price, $this->settings['lines_last']['color']);
        }

    }

    /**
     * рисуем скользящие средние EMA SMA
     */
    protected function addMA()
    {
        //перебор подготовленых данных и отрисовка каждой скользящей средней
        $y_sma_name = 15;
        foreach ($this->MA as $ma) {

            //Последнее значение МА на оси Х
            if ($this->settings['mav'] == 1) {
                $this->drawPolygon($ma['data'][0], round($ma['data'][0], 2), $ma['color']);
            } else {
                $count = count($ma['data']) - 1;
                $x = $this->settings['aw'];
                for ($n = 0; $n < $count; $n++) {

                    $this->drawMA($ma['data'][$n + 1], $ma['data'][$n], $x, $ma['color']);
                    $x = $x - $this->settings['barw'];
                }
            }

            imagestring($this->img, 2, 20, $y_sma_name, $ma['name'], $ma['color']);
            $y_sma_name = $y_sma_name + 12;

        }
    }


    /**
     * Расчет точек для отриоовки горизонтальных ценовых линий
     */
    public function calculateLines()
    {
        $days = [];
        $key = 0;
        foreach ($this->feed->t as $timestamp) {

            // группировка данных по дням для формирования точек линий (open,high,low,close)
            $days[date('Ymd', $timestamp)]['datakey'][] = $key;
            $days[date('Ymd', $timestamp)]['o'][] = $this->feed->o[$key];
            $days[date('Ymd', $timestamp)]['h'][] = $this->feed->h[$key];
            $days[date('Ymd', $timestamp)]['l'][] = $this->feed->l[$key];
            $days[date('Ymd', $timestamp)]['c'][] = $this->feed->c[$key];

            $prev_timestamp = $timestamp;
            $key++;
        }
        //перебор дней со своими значениями для формирования масива точек линий
        if ($this->settings['tf'] != 'd' && $this->settings['tf'] != 'w') {

            $key = 0;
            $lines = [];
            foreach ($days as $date => $val) {

                $keys = [ //ключи нужных значений точек в текущем дне
                    'h' => array_keys($val['h'], max($val['h']))[0],
                    'l' => array_keys($val['l'], min($val['l']))[0],
                    'o' => count($val['o']) - 1,
                    'c' => 0
                ];

                //массив точек начала линий со значением цены (x - номер бара по счету с начала)
                if ($this->settings['lines_hi']['check']) {
                    $lines[] = ['type' => 'hi', 'price' => $val['h'][$keys['h']], 'x' => $val['datakey'][$keys['h']]];
                }

                if ($this->settings['lines_lo']['check']) {
                    $lines[] = ['type' => 'lo', 'price' => $val['l'][$keys['l']], 'x' => $val['datakey'][$keys['l']]];
                }

                if ($this->settings['lines_op']['check']) {
                    $lines[] = ['type' => 'op', 'price' => $val['o'][$keys['o']], 'x' => $val['datakey'][$keys['o']]];
                }

                if ($this->settings['lines_cl']['check']) {
                    $lines[] = ['type' => 'cl', 'price' => $val['c'][$keys['c']], 'x' => $val['datakey'][$keys['c']]];
                }

                $key++;
                if ($key == $this->settings['lines_d']) {
                    break;
                }
            }
            $this->lines = $lines;
        }
    }


    /**
     * расчет данных для отрисовки скользящих средних
     */
    public function calculateMA()
    {
        if (isset($this->settings['sma1']['choice']) && $this->settings['sma1']['choice']) {

            $this->MA[] = [
                'name' => 'sma ' . $this->settings['sma1']['choice'],
                'data' => $this->getSMA($this->feed->c, $this->settings['sma1']['choice'], $this->bars_count),
                'color' => $this->settings['sma1']['color']
            ];
        }

        if (isset($this->settings['sma2']['choice']) && $this->settings['sma2']['choice']) {

            $this->MA[] = [
                'name' => 'sma ' . $this->settings['sma2']['choice'],
                'data' => $this->getSMA($this->feed->c, $this->settings['sma2']['choice'], $this->bars_count),
                'color' => $this->settings['sma2']['color']
            ];
        }

        if (isset($this->settings['sma3']['choice']) && $this->settings['sma3']['choice']) {

            $this->MA[] = [
                'name' => 'sma ' . $this->settings['sma3']['choice'],
                'data' => $this->getSMA($this->feed->c, $this->settings['sma3']['choice'], $this->bars_count),
                'color' => $this->settings['sma3']['color']
            ];
        }

        if (isset($this->settings['ema1']['choice']) && $this->settings['ema1']['choice']) {

            $this->MA[] = [
                'name' => 'ema ' . $this->settings['ema1']['choice'],
                'data' => $this->getEMA($this->feed->c, $this->settings['ema1']['choice'], $this->bars_count),
                'color' => $this->settings['ema1']['color']
            ];
        }

        if (isset($this->settings['ema2']['choice']) && $this->settings['ema2']['choice']) {

            $this->MA[] = [
                'name' => 'ema ' . $this->settings['ema1']['choice'],
                'data' => $this->getEMA($this->feed->c, $this->settings['ema1']['choice'], $this->bars_count),
                'color' => $this->settings['ema1']['color']
            ];
        }

        if (isset($this->settings['ema3']['choice']) && $this->settings['ema3']['choice']) {

            $this->MA[] = [
                'name' => 'ema ' . $this->settings['ema3']['choice'],
                'data' => $this->getEMA($this->feed->c, $this->settings['ema3']['choice'], $this->bars_count),
                'color' => $this->settings['ema3']['color']
            ];
        }

    }


    public function getSMA($data, $period, $bars_count)
    {
        $A = [];
        $count = count($data) - $period;
        for ($i = 0; $i <= $count; $i++) {
            $A[] = round(array_sum(array_slice($data, $i, $period)) / $period, 2);
        }
        return array_slice($A, 0, $bars_count);
    }

    public function getEMA($data, $period, $bars_count)
    {
        $A = [];
        $data = array_reverse($data);
        for ($i = 0; $i < count($data); $i++) {
            if ($i == $period - 1) {
                $A[] = round(array_sum(array_slice($data, 0, $period)) / $period, 2);
            } else {
                $A[] = $data[$i] * 2 / ($period + 1) + end($A) * (1 - 2 / ($period + 1));
            }
        }
        return array_slice(array_reverse($A), 0, $bars_count);
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @return ChartDataFeeder
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * если нет данных для отрисовки графика
     */
    public function addNoData()
    {
        imagestring($this->img, 8, $this->settings['w'] / 2 - 100, $this->settings['h'] / 2, 'Chart data not available',
            $this->settings['setka']);
    }

    /**
     * Вывод изображения
     */
    public function output()
    {
        header("Content-Type: image/png");
        imagepng($this->img);
        imagedestroy($this->img);
    }


}
