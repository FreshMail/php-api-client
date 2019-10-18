<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Response;

interface HttpResponseInterface extends ResponseInterface
{
    public function getStatusCode(): int;
}