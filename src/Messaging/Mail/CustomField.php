<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging\Mail;

class CustomField
{
    /**
     * @var string
     */
    private $personalizationTag;

    /**
     * @var string
     */
    private $value;

    public function  __construct(string $personalizationTag, string $value)
    {
        $this->personalizationTag = trim($personalizationTag);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getPersonalizationTag(): string
    {
        return $this->personalizationTag;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}