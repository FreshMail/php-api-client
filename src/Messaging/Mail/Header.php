<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging\Mail;

use FreshMail\Api\Client\Messaging\Mail\Exception\InvalidHeaderException;

class Header
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    public function __construct(string $name, string $value)
    {
        $name = trim($name);

        if (!$name) {
            throw new InvalidHeaderException(sprintf('Empty header name'));
        }

        if (str_replace(" \n\r", '', $name) != $name) {
            throw new InvalidHeaderException(sprintf('Invalid header name "%s", header contains whitespaces', $name));
        }

        if (strtolower(substr($name, 0,2)) != 'x-') {
            $name = 'X-'.$name;
        }

        $this->name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
        $this->value = trim($value);
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
    public function getValue(): string
    {
        return $this->value;
    }
}