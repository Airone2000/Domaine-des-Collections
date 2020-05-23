<?php

namespace App\Mailer;

use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

trait ConstructorTrait
{
    public function __construct(Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);
        $this->from('default@default');
    }
}