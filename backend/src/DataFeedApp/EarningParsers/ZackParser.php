<?php

namespace App\DataFeedApp\EarningParsers;


use App\Entity\FeedImport\Basic\FeedBasicEarnings;

class ZackParser extends BaseParser
{
    /**
     * @param \DateTime $datetime
     * @return FeedBasicEarnings[]
     * @throws \Exception
     */
    protected function getContent(\DateTime $datetime){

       $date =  $datetime->format('Y-m-d');

        $url = 'http://www.zacks.com/includes/classes/z2_class_calendarfunctions_data.php?calltype=eventscal&date=' .
            strtotime($date . ' 05:00:00') . '&type=1';

        $feedBaseEarnings = [];

        $c = json_decode(@file_get_contents($url), true);
        $str = '';

        if (isset($c['data'])) {
            foreach ($c['data'] as $data) {

                if ($surp = $this->getTextBetweenTags($data[6], 'div')) {
                    $surp = explode(" ", $surp);
                    $surp_d = $surp[0];
                    $surp_p = str_replace(array('(', ')', '%'), '', $surp[1]);
                } else {
                    $surp_d = '';
                    $surp_p = '';
                }

                $feedBaseEarning = new FeedBasicEarnings();
                $feedBaseEarning->setId($this->getTextBetweenTags($data[0], 'span'));
                $feedBaseEarning->setEarn(new \DateTime($date));
                $feedBaseEarning->setEarntime($data[3]);
                $feedBaseEarning->setEps(str_replace('$', '', $data[5]));
                $feedBaseEarning->setEpsEst(str_replace('$', '', $data[4]));
                $feedBaseEarning->setEpsSurprise($surp_d);
                $feedBaseEarning->setEpsSurprisePercent($surp_p);

                $feedBaseEarnings[] = $feedBaseEarning;

            }
        }



        return $feedBaseEarnings;
    }

    protected function getTextBetweenTags($string, $tagname)
    {
        $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
        preg_match($pattern, $string, $matches);

        if ($matches) {
            return $matches[1];
        } else {
            return false;
        }

    }
}
