<?php

namespace App\Mailer;

class TemplatedEmail extends \Symfony\Bridge\Twig\Mime\TemplatedEmail
{
    use ConstructorTrait;
}