<?php
namespace App\Command;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\Entity\Feed\MainLevel1;
use App\Entity\Feed\MainTickers;
use App\Entity\FeedImport\Basic\FeedBasicTickers;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SendEmailCommand extends BaseFeedImportCommand
{
    protected $mailer;

    public function getDefaultDefinition()
    {
        return '* * * * *';
    }

    public function __construct(MailerInterface $mailer, EntityManagerInterface $em, ?string $name = null)
    {
        $this->mailer = $mailer;
        parent::__construct($em, $name);
    }



    protected function configure()
    {
        $this
            ->setName('cron:email-send')
            ->setDescription('S');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $users = $qb
            ->select('u')
            ->from(User::class,'u')
            ->where('u.emailing IS NULL')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;

        /** @var User $user */
        foreach ($users as $user) {
            $email = (new TemplatedEmail())
                ->from(new Address('support@stock-watcher.com', 'Support'))
                ->to($user->getEmail())
                ->subject('New Stock-Watcher Version')
                ->htmlTemplate('email/new_version.html')
            ;

            $this->mailer->send($email);
            $user->setEmailing(new \DateTime());
        }

        $this->addMessage(count($users));

    }

}
