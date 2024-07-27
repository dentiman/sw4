<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 10.12.16
 * Time: 13:29
 */

namespace App\Command\FeedImport;

use App\Entity\Feed\MainGrid;
use App\Entity\Feed\MainTickers;
use App\Entity\FeedImport\Basic\FeedBasicCalculatedGrid;
use App\Entity\FeedImport\Basic\FeedBasicTickers;
use Dentiman\ScheduleBundle\Command\BasicScheduleCommand;
use Dentiman\ScheduleBundle\Command\CronCommandInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class  BaseFeedImportCommand extends BasicScheduleCommand implements CronCommandInterface
{
    /**
     * @var bool
     */
    private $dbConnection = false;

    /**
     * @return \Doctrine\DBAL\Driver\Connection
     */
    protected function getConnection()
    {

        if ($this->dbConnection == false) {

            $this->dbConnection = $this->em->getConnection();
        }

        return $this->dbConnection;

    }


    protected function dbQuery($query)
    {
        $statement = $this->getConnection()->prepare($query);
        $statement->execute();
        return $statement->rowCount();

    }



    /**
     * @param OutputInterface $output
     * @param string $message
     */
    private function writeError(OutputInterface $output, $message)
    {
        $output->writeln(sprintf("\n<error>%s</error>", $message));
    }


    /**
     * @param $className
     */
    protected function truncateTable($className)
    {
        $this->getRepository($className)
            ->createQueryBuilder('c')->delete()->getQuery()->execute();
    }


    protected function replaceInto($sourceTable, $targetTable)
    {
        $this->dbQuery("REPLACE INTO $targetTable SELECT * FROM $sourceTable");
    }

    protected function getRowsCount($className,$idFieldName = 'id')
    {
       return $this->getRepository($className)
            ->createQueryBuilder('c')
            ->select("count(c.".$idFieldName.")")
            ->getQuery()
            ->getSingleScalarResult()
            ;

    }



    /** array of tickets
     * @return array
     */
    protected function getBaseTickers()
    {
        $tickers = $this->em->getRepository(MainTickers::class)->findAll();
        $array = [];
        foreach ($tickers as $ticker) {
            $array[] = $ticker->getId();
        }

        return $array;
    }

    /**
     * @return Serializer
     */
    protected function getCsvSerializer()
    {
        return new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    }

    protected function updateBaseGrid()
    {

        //TODO:: test how many time take running this method! if long - make throw another entity
        $this->truncateTable(FeedBasicCalculatedGrid::class);
        $this->dbQuery("REPLACE INTO  `feed_basic_calulated_grid` SELECT * FROM feed_main_tickers " .
            "LEFT JOIN  `feed_main_tech` USING(id) " .
            "LEFT JOIN  `feed_main_tmp` USING(id) " .
            "LEFT JOIN  `feed_main_level1` USING(id) " .
            "LEFT JOIN  `feed_main_earnings` USING(id) ".
            "LEFT JOIN  `feed_main_premarket` USING(id) ".
            "LEFT JOIN  `feed_basic_calculated_data` USING(id) "

        );

        $feedBasicGridRecords = $this->getRepository(FeedBasicCalculatedGrid::class)->findAll();

        /** @var FeedBasicCalculatedGrid $feedBasicGrid */
        foreach ($feedBasicGridRecords as $feedBasicGrid) {
                $feedBasicGrid->calculate();
        }

        $this->getEntityManager()->flush();

        $this->truncateTable(MainGrid::class);
        $this->replaceInto('feed_basic_calulated_grid','feed_main_grid');

        $this->addMessage('main_grid_updated');
    }

}
