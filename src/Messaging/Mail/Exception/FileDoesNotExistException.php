<?php

namespace FreshMail\Api\Client\Messaging\Mail\Exception;

/**
 * Class FileDoesNotExistException
 * @package FreshMail\Api\Client\Messaging\Mail\Exception
 */
class FileDoesNotExistException extends \Exception
{
    /**
     * FileDoesNotExistException constructor.
     * @param string $filepath
     */
    public function __construct(string $filepath)
    {
        parent::__construct(sprintf('Invalid path to file or file does not exist, path: %s', $filepath));
    }
}
