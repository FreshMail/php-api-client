<?php

namespace FreshMail\Api\Client\Messaging\Mail;

use FreshMail\Api\Client\Messaging\Mail\Exception\ExternalFileException;
use FreshMail\Api\Client\Messaging\Mail\Exception\FileDoesNotExistException;

/**
 * Class Attachment
 * @package FreshMail\Api\Client\Messaging\Mail
 */
class LocalFileAttachment implements AttachmentInterface
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
     * @param string $filepath
     * @param string|null $name
     * @throws FileDoesNotExistException
     */
    public function __construct(string $filepath, string $name = null)
    {
        $this->validate($filepath);
        $this->name = $name ?? basename($filepath);
        $this->content = base64_encode(rtrim(file_get_contents($filepath)));
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

    /**
     * @param string $filepath
     * @throws FileDoesNotExistException
     */
    private function validate(string $filepath)
    {
        if (!realpath($filepath)) {
            throw new FileDoesNotExistException($filepath);
        }
    }
}
