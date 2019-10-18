<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging\Mail;

use MyCLabs\Enum\Enum;

class ContentType extends Enum
{
    const HTML = 'text/html';
    const TEXT = 'text/plain';
}