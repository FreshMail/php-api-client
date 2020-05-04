<?php

namespace FreshMail\Api\Client\Messaging\Mail;

use FreshMail\Api\Client\Messaging\Mail\Exception\InvalidContentBodyException;

/**
 * Class Base64Attachment
 * @package FreshMail\Api\Client\Messaging\Mail
 */
class Base64Attachment implements AttachmentInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $content;

    /**
     * Attachment constructor.
     * @param string $name
     * @param string $content
     * @throws InvalidContentBodyException
     */
    public function __construct(string $name, string $content)
    {
        if (base64_encode(base64_decode($content, true)) !== $content) {
            throw new InvalidContentBodyException(sprintf('Attachment\'s body with name: %s is invalid base64 string', $name));
        }

        $this->name = $name;
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return ['name' => $this->name, 'content' => $this->content];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
