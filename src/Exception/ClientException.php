<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Exception;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClientException extends TransferException
{
    /** @var RequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    public function __construct(
        $message,
        RequestInterface $request,
        ResponseInterface $response,
        \Exception $previous = null,
        array $handlerContext = []
    ) {
        parent::__construct($message, $response->getStatusCode(), $previous);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return (bool) $this->response;
    }

    /**
     * @return bool
     */
    public function hasRequest()
    {
        return (bool) $this->request;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }


}