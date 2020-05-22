<?php

namespace App\Mailer;

use Html2Text\Html2Text;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

class Email extends \Symfony\Component\Mime\Email
{
    public function __construct(Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);
        $this->from('default@default');
    }

    public function html($body, string $charset = 'utf-8')
    {
        if (null === $this->getTextBody()) {
            $textBody = (new Html2Text($body))->getText();
            $this->text($textBody);
        }

        return parent::html($body, $charset);
    }
}