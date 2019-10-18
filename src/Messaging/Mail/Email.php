<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging\Mail;

class Email
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $domain;

    /**
     * From constructor.
     * @param string $email
     * @param string $name
     */
    public function __construct(string $email)
    {
        $this->email = mb_strtolower(trim($email));
        $this->domain = substr(strrchr($this->email, "@"), 1);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }
}