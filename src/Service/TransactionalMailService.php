<?php

namespace FreshMail\Api\Client\Service;

use FreshMail\Api\Client\Domain\Messaging\Mail\MailBag;

interface TransactionalMailService
{
    public function send(MailBag $mailBag);
}