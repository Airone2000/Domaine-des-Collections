<?php

namespace App\Mailer;

use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

class Email extends \Symfony\Component\Mime\Email
{
    use ConstructorTrait;
}