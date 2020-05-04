<?php

namespace FreshMail\Api\Client\Messaging\Mail;

interface AttachmentInterface
{
    public function getName(): string;

    public function getContent(): string;

    public function toArray(): array;
}