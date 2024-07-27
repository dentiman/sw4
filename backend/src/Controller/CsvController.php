<?php

namespace App\Controller;

use App\Entity\Feed\MainGrid;
use App\Entity\Feed\MainLevel1;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CsvController extends AbstractController
{
    /**
     * @Route("/api/yahoo/csv", name="yahoo_csv")
     */
    public function index()
    {

       $level1 =  $this->getDoctrine()->getRepository(MainLevel1::class)->findAll();
        $row ='';
        foreach ($level1 as $item) {
            $row .=
                $item->getId() .','.
                $item->getPrice() .','.
                $item->getOp() .','.
                $item->getHi() .','.
                $item->getLo() .','.
                $item->getChp() .','.
                $item->getCh() .','.
                 '' .','.
                $item->getBid() .','.
                $item->getAsk() .','.
                $item->getBidsize() .','.
                $item->getAsksize() .','.
                $item->getTcount() .','.
                $item->getVol()."\n";
                ;
       }

        $response = new Response($row);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;

    }


    /**
     * @Route("/api/grid/csv", name="grid_csv_views")
     */
    public function gridCsvAction()
    {

        $level1 =  $this->getDoctrine()->getRepository(MainGrid::class)->findAll();
        $row ='';
        /** @var MainGrid $item */
        foreach ($level1 as $item) {
            $row .=
                $item->getId() .','.
                '"'.$item->getName().'"'.','.        //"name"
                $item->getExchange() .','.         //"e"
                $item->getSector() .','.        //"sec"
                '"'.$item->getInd().'"'.','.         //"ind"
                '"'.$item->getCountry() .'"'.','.       //"coun"
                $item->getIndex() .','.       //"i"
                ($item->getIpo() ? $item->getIpo()->format('Y-m-d'): '') .','.        //"ipo"
                ','.        //"cl1"
                ','.        //"op1"
                ','.        //"hi1"
                ','.        //"lo1"
                ','.        //"vol1"
                ','.        //"cl2"
                ','.        //"op2"
                ','.       //"hi2"
                ','.        //"lo2"
                ','.        //"vol2"
                ','.        //"cl3"
               ','.       //"op3"
                ','.        //"hi3"
                ','.        //"lo3"
                ','.       //"vol3"
                ','.       //"cl4"
                ','.        //"op4"
                ','.       //"hi4"
                ','.        //"lo4"
                ','.        //"vol4"
                $item->getMarketCap() .','.        //"mc"
                $item->getForwardPE() .','.        //"pe"
                $item->getForwardPE() .','.        //"fpe"
                    ','.        //"epsf"
                $item->getSharesOutstanding() .','.        //"aut"
                 ','.       //"sfloat"
                ','.       //"insider"
                ','.       //"fshort"
                ','.       //"shratio"
                ','.        //"pw"
                ','.        //"pm"
                ','.        //"pq"
                ','.        //"ph"
                ','.        //"py"
                $item->getAtr() .','.       //"atr"
                 ','.        //"sma20pc"
                $item->getFiftyDayAverageChangePercent() .','.       //"sma50pc"
                $item->getTwoHundredDayAverageChangePercent() .','.        //"sma200pc"
                ','.        //"hi50pc"
                ','.        //"lo50pc"
                $item->getFiftyTwoWeekHighChangePercent() .','.        //"hi52pc"
                $item->getFiftyTwoWeekLowChangePercent() .','.        //"lo52pc"
                round($item->getAverageDailyVolume3Month()/1000,2) .','.       //"avvo"
                $item->getPrice() .','.        //"price"
                $item->getOp() .','.        //"op"
                $item->getHi() .','.        //"hi"
                $item->getLo() .','.        //"lo"
                $item->getChp() .','.        //"chp"
                $item->getCh() .','.        //"ch"
                ($item->getTtime() ? $item->getTtime()->format('Y-m-d H:i'): '') .','.        //"ttime"
                $item->getBid() .','.        //"bid"
                $item->getAsk() .','.        //"ask"
                $item->getBidsize() .','.        //"bidsize"
                $item->getAsksize() .','.        //"asksize"
                $item->getTcount() .','.       //"tcount"
                $item->getVol() .','.       //"vol"
                ($item->getEarn()? $item->getEarn()->format('Y-m-d'): '') .','.       //"earn"
                $item->getEarntime() .','.        //"earn_time"
                $item->getEps() .','.        //"eps"
                $item->getEpsEst() .','.       //"eps_est"
                $item->getEpsSurprise() .','.       //"eps_surprise"
                $item->getEpsSurprisePercent() .','.       //"eps_surprise_percent"
                $item->getPvol() .','.        //"pvol"
                $item->getPtcount() .','.       //"ptcount"
                $item->getPprice() .','.        //"pprice"
                $item->getPchp() .','.        //"pchp"
                $item->getPch() .','.        //"pch"
                ','.        //"price1m"
                ','.        //"vol1m"
                ','.        //"tcount1m"
                ','.        //"price5m"
                ','.        //"vol5m"
                ','.        //"tcount5m"
                ','.        //"price10m"
                ','.       //"vol10m"
                ','.        //"tcount10m"
                ','.        //"price15m"
                ','.        //"vol15m"
                "\r\n"        //"tcount15m"

;
        }
        $response = new Response($row);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }
}
