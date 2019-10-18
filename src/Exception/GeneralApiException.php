<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GeneralApiException extends \Exception
{

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function hasRequest()
    {
        return false;
    }

}