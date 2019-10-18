<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Response;

interface ResponseInterface
{
    public function isSuccess(): bool;

    public function isError(): bool;

    public function getData(): array;

}