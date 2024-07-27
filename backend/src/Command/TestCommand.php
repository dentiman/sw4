<?php

namespace App\Command;

use App\DataFeedApp\Bar\Read\BarReader;
use App\DataFeedApp\Bar\Read\Sources\IqFeedBarSource;
use App\DataFeedApp\Bar\Read\Sources\YahooBarSource;
use App\DataFeedApp\Bar\Storage\DailyBarStorage;
use App\DataFeedApp\Helper;
use Intervention\Image\Gd\Color;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request as TelegramRequest;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Contracts\Cache\CacheInterface;
use Payum\Core\Bridge\Doctrine\Storage\DoctrineStorage;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';

    protected $mailer;

    public function __construct( MailerInterface $mailer, string $name = null)
    {
        $this->mailer = $mailer;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $telegram = new Telegram("613211651:AAGZ-wT07vzJDANhbWHzASwTV9aMEs0ftk8", "dentiman_bot");
        $result = TelegramRequest::sendMessage([
            'chat_id' => '-1001370496379',
            'text' => 'dd'
        ]);
        return 0;
    }
}
