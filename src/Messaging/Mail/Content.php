<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging\Mail;

use FreshMail\Api\Client\Messaging\Mail\Exception\InvalidContentBodyException;

class Content
{
    /**
     * @var ContentType
     */
    private $contentType;

    /**
     * @var string
     */
    private $body;

    const AVAILABLE_TYPES = [
        'text/plain',
        'text/html'
    ];

    public function __construct(ContentType $contentType, string $body)
    {
        $body = trim($body);

        if (!$body) {
            throw new InvalidContentBodyException(sprintf('Cannot add empty body in content, content-type: %s', $contentType->getValue()));
        }

        $this->contentType = $contentType;
        $this->body = $body;
    }

    /**
     * @return ContentType
     */
    public function getContentType(): ContentType
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}