<?php

namespace App\Mailer\Transport\Mailjet;

use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

class MailjetTransportFactory extends AbstractTransportFactory
{

    protected function getSupportedSchemes(): array
    {
        return ['mailjet', 'mailjet+api'];
    }

    public function create(Dsn $dsn): TransportInterface
    {
        $scheme = $dsn->getScheme();

        if ('mailjet+api' === $scheme || 'mailjet' === $scheme) {
            $user = $this->getUser($dsn);
            $password = $this->getPassword($dsn);
            $version = $dsn->getOption('version', '3.1');
            $version = ltrim($version, 'v');
            $version = 'v'.$version;

            $mjClient = new \Mailjet\Client($user, $password, true, [
                'version' => $version,
            ]);

            return new MailjetApiTransport($mjClient, $dsn->getOption('sender'), $this->client, $this->dispatcher, $this->logger);
        }

        throw new UnsupportedSchemeException($dsn, 'mailjet', $this->getSupportedSchemes());
    }
}