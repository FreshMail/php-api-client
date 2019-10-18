<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging;

interface ResponseInterface
{
    public function isSuccess(): bool;

    public function isError(): bool;

    public function getStatusCode(): int;

    public function get(): array;

}