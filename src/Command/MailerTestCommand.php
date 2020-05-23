<?php

namespace App\Command;

use App\Entity\User;
use App\Mailer\Email;
use App\Mailer\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        parent::__construct(self::$defaultName);
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
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
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'username' => 'erwan'
        ]);
        $email = new TemplatedEmail();
        $email
            ->to('r.wan.guillou@gmail.com')
            ->subject('Adoccia\'s email test')
            ->htmlTemplate('emails/welcome.html.twig')
            ->context(['user' => $user])
        ;
        $this->mailer->send($email);
    }
}
