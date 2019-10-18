<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Service\Messaging;

use FreshMail\Api\Client\Messaging\Mail\MailBag;
use FreshMail\Api\Client\Response\HttpResponse;
use FreshMail\Api\Client\FreshMailApiClient;

class Mail extends FreshMailApiClient
{
    public function send(MailBag $mailBag): HttpResponse
    {
        return $this->requestExecutor->post('messaging/emails', $mailBag);
    }
}