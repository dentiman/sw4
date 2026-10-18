<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.12.16
 * Time: 15:37
 */

namespace App\Command\FeedImport;

use App\Entity\FeedImport\Basic\FeedBasicEarnings;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class UpdateEarningsCommand extends BaseFeedImportCommand
{

    protected $days = -3;

    public function getDefaultDefinition()
    {
        return '20 1 * * *';
    }


    protected function configure()
    {
        $this
            ->setName('cron:feed:earnings:today')
            ->setDescription('Update next week earnings for tickers or current earnings')
            ->addArgument('days', InputArgument::OPTIONAL, 'days 5');
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
//        $days = -1;
//        // Improve logic beacose of rest days
//        if($input->getArgument('days')) {
//            $days =  $input->getArgument('days');
//        }

        $days = $this->days;

        $this->truncateTable(FeedBasicEarnings::class);

        $feedBaseEarnings = $this->getDays($days);

        foreach ($feedBaseEarnings as $feedBaseEarning) {
           $exist = $this->em->getRepository(FeedBasicEarnings::class)
                ->find($feedBaseEarning->getId());

           if(!$exist)
            $this->em->persist($feedBaseEarning);
        }

        $this->em->flush();

        $earningsResult = $this->getRepository(FeedBasicEarnings::class)
            ->createQueryBuilder('e')
            ->select("e.earn as earningDate, COUNT(e.id) as count")
            ->groupBy("earningDate")
            ->getQuery()
            ->getResult();


        $json = [];
        foreach ($earningsResult as $earnTime) {

            /** @var \DateTime $date */
            $date = $earnTime['earningDate'];
            $json[$date->format("Y-m-d")] = $earnTime['count'];
        }

        $this->addMessage(json_encode($json));
        $this->replaceInto("feed_basic_earnings", "feed_main_earnings");


    }

    /**
     * @param $days
     * @return FeedBasicEarnings[]
     * @throws \Exception
     */
    public function getDays($days)
    {
        $feedBaseEarnings = [];

        if ($days >= 0) {
            $start = 0;
            $end = $days;
            $day = new \DateTime();
        } else {
            $start = $days;
            $end = 0;
            $day = new \DateTime('-' . ($days * -1) . ' day');
        }


        for ($i = $start; $i <= $end; $i++) {

            $feedBaseEarnings = array_merge($feedBaseEarnings, $this->getOneDay($day));
            $day->modify('+1 day');
        }

        return $feedBaseEarnings;
    }


    /**
     * @param \DateTime $datetime
     * @return FeedBasicEarnings[]
     * @throws \Exception
     */
    protected function getOneDay(\DateTime $datetime)
    {

        $date = $datetime->format('Y-m-d');

        $url = 'http://www.zacks.com/includes/classes/z2_class_calendarfunctions_data.php?calltype=eventscal&date=' .
            strtotime($date . ' 05:00:00') . '&type=1';

        $feedBaseEarnings = [];

        $content = str_replace('window.app_data = ','',@file_get_contents($url));
        $c = json_decode( $content , true);


        if (isset($c['data'])) {

            foreach ($c['data'] as $data) {
               // print_r($data);
                if ($surp = $this->getTextBetweenTags($data[6], 'div')) {
                    $surp = explode(" ", $surp);
                    $surp_d = $surp[0];
                    $surp_p = str_replace(array('(', ')', '%'), '', $surp[1]);
                } else {
                    $surp_d = '';
                    $surp_p = '';
                }


                if($ticker = $this->getTicker($data[0])) {
                    $feedBaseEarning = new FeedBasicEarnings();
                    $feedBaseEarning->setId($ticker);
                    $feedBaseEarning->setEarn(new \DateTime($date));
                    $feedBaseEarning->setEarntime($this->validateValue($data[3]));
                    $feedBaseEarning->setEps($this->validateValue($data[5]));
                    $feedBaseEarning->setEpsEst($this->validateValue($data[4]));
                    $feedBaseEarning->setEpsSurprise($this->validateValue($surp_d));
                    $feedBaseEarning->setEpsSurprisePercent($this->validateValue($surp_p));

                    $feedBaseEarnings[] = $feedBaseEarning;
                }


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
            return null;
        }

    }


    protected function getTicker($string)
    {
      $part = explode('overquote-symbol">',$string);
      if(count($part)> 1) {
          $ticker = explode('<span class="sr-only">',$part[1]);
          return $ticker[0];
      }
        return  null;
    }

    protected function validateValue($value)
    {
        $value = str_replace('--', '', $value);
        $value = str_replace('$', '', $value);
        if (strlen($value) > 0) {
            return $value;
        }
        return null;
    }
}
