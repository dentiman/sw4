<?php
namespace App\Command\FeedImport;

use App\Entity\Feed\MainTmp;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class UpdateTmpCommand extends BaseFeedImportCommand
{

    public function getDefaultDefinition()
    {
        return '0 1 * * *';
    }



    protected function configure()
    {
        $this
            ->setName('cron:feed:tmp')
            ->setDescription('Update from csv');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(MainTmp::class);
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);


        $str =  file_get_contents(__DIR__.'/../../../config/packages/feed/tmp.csv');

        $data = $serializer->decode($str, 'csv');

        foreach ($data as $item) {



            $tmpModel = new MainTmp();
            $tmpModel->setId($this->validate($item['ticker']));
            $tmpModel->setSector($this->validate($item['sector']));
            $tmpModel->setInd($this->validate($item['ind']));
            $tmpModel->setCountry($this->validate($item['country']));
            $tmpModel->setAtr($this->validate($item['atr']));

            if( strlen($item['ipo']) && $item['ipo'] != 'NULL') {

                $date = new \DateTime($item['ipo']);

                if($date->format('Y')*1 > 1900) {
                    $tmpModel->setIpo($date );
                }

            }

            $tmpModel->setIndex($this->validate($item['index']));
            $this->em->persist($tmpModel);
        }

        $this->em->flush();
        return 0;
    }


    protected function validate($value)
    {
        if(strlen($value) > 0 && $value != 'NULL') return $value;

        return null;
    }

}
