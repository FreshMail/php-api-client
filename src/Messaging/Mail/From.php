<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Messaging\Mail;

class From
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * From constructor.
     * @param string $email
     * @param string $name
     */
    public function __construct(Email $email, string $name)
    {
        $this->email = $email;
        $this->name = trim($name);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email->getEmail();
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->email->getDomain();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}