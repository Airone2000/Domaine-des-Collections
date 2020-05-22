<?php

namespace App\Command;

use App\Mailer\Email;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;

class MailerTestCommand extends Command
{
    protected static $defaultName = 'mailer:test';
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        parent::__construct(self::$defaultName);
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Sends an email to make sure configuration is right')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->sendSyncEmail();

        $io->success('The command has finished its job. Check out your mailbox.');

        return 0;
    }

    private function sendSyncEmail()
    {
        $email = new Email();
        $email
            ->to('r.wan.guillou@gmail.com')
            ->subject('Adoccia\'s email test')
            ->html('<p>coucou</p>')
        ;
        $this->mailer->send($email);
    }
}
