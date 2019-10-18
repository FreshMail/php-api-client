<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging\Mail;

use FreshMail\Api\Client\Messaging\Mail\Exception\InvalidArrayKey;
use FreshMail\Api\Client\Messaging\Mail\Exception\InvalidCustomFieldException;
use FreshMail\Api\Client\Messaging\Mail\Exception\InvalidHeaderException;
use FreshMail\Api\Client\Messaging\Mail\Exception\InvalidValue;
use FreshMail\Api\Client\Messaging\Mail\Exception\InvalidValueInCustomFields;

class Recipient
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var CustomField[]
     */
    private $customFields = [];

    /**
     * @var Header[]
     */
    private $headers = [];

    /**
     * Recipient constructor.
     * @param $email
     * @param $customFields
     * @param $headers
     */
    public function __construct(string $email, array $customFields = [], array $headers = [])
    {
        $this->email = $email;

        try {
            foreach ($customFields as $name => $value) {
                $this->customFields[] = new CustomField($name, $value);
            }
        } catch (\TypeError $exception) {
            throw new InvalidCustomFieldException(sprintf('Unable to create Custom Fields, name: "%s" (%s), value: "%s" (%s)', $name, gettype($name), $value, gettype($value)));
        }

        try {
            foreach ($headers as $name => $value) {
                $this->headers[] = new Header($name, $value);
            }
        } catch (\TypeError $exception) {
            throw new InvalidHeaderException(sprintf('Unable to create Header, name: "%s" (%s), value: "%s" (%s)', $name, gettype($name), $value, gettype($value)));
        }
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return CustomField[]
     */
    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}