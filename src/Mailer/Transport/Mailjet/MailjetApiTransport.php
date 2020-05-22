<?php

namespace App\Mailer\Transport\Mailjet;

use Mailjet\Client;
use Mailjet\Resources;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractApiTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MailjetApiTransport extends AbstractApiTransport
{
    /**
     * @var Client
     */
    private Client $mjClient;
    private ?array $sender;

    public function __construct(Client $mjClient, ?array $sender, HttpClientInterface $client = null, EventDispatcherInterface $dispatcher = null, LoggerInterface $logger = null)
    {
        parent::__construct($client, $dispatcher, $logger);
        $this->mjClient = $mjClient;
        $this->sender = $sender;
    }

    protected function doSendApi(SentMessage $sentMessage, Email $email, Envelope $envelope): ResponseInterface
    {
        /* @var Address $sender */
        $sender = $email->getFrom()[0] ?? null;

        # SENDER
        if ($sender->getAddress() === 'default@default') {
            $senderName = $this->sender['name'];
            $senderEmail = $this->sender['email'];
        } else {
            $senderName = $sender->getName();
            $senderEmail = $sender->getAddress();
        }

        # RECIPIENT
        $recipient = $email->getTo()[0];
        $recipientName = $recipient->getName();
        $recipientEmail = $recipient->getAddress();

        # PAYLOAD
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $senderEmail,
                        'Name' => $senderName,
                    ],
                    'To' => [
                        [
                            'Email' => $recipientEmail,
                            'Name' => $recipientName,
                        ]
                    ],
                    'Subject' => $email->getSubject(),
                    'TextPart' => $email->getTextBody(),
                    'HTMLPart' => $email->getHtmlBody(),
                ]
            ]
        ];

        # Mailjet response third party client
        $response = $this->mjClient->post(Resources::$Email, ['body' => $body]);
        $expectedResponse = new MockResponse($response->getBody(), [
            'http_code' => $response->getStatus()
        ]);

        $responseData = $response->getData();
        if ($response->success()) {
            $sentMessage->setMessageId(
                $responseData['Messages'][0]['To'][0]['MessageID']
            );
        } else {
            $errors = array_map(fn($error) => $error['ErrorMessage'] , $responseData['Messages'][0]['Errors']);
            $errors = implode(' ; ', $errors);
            throw new HttpTransportException('Unable to send email ('.$response->getStatus().'). Errors : '.$errors, $expectedResponse);
        }

        # NativeResponse should be used internally.
        return $expectedResponse;
    }


    public function __toString(): string
    {
        return 'api.mailjet.com'; // as defined by the client
    }

}