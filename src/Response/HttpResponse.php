<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Response;

use FreshMail\Api\Client\Exception\InvalidJsonDecodeException;

class HttpResponse implements HttpResponseInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;

    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        $statusCode = $this->response->getStatusCode();
        return ($statusCode >= 200 && $statusCode < 300);
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return !$this->isSuccess();
    }

    /**
     * @return array
     */
    public function getJson(): string
    {
        $stream = $this->response->getBody();
        $stream->rewind();

        return $stream->read($stream->getSize());
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->jsonDecode($this->getJson());
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getPsr7Response(): \Psr\Http\Message\ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param string $json
     * @return array
     * @throws InvalidJsonDecodeException
     */
    private function jsonDecode(string $json): array
    {
        $data = \json_decode($json, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidJsonDecodeException(
                sprintf('json_decode error: %s, string to decode: %s', json_last_error_msg(), $json)
            );
        }

        return $data;
    }

    public function __toString()
    {
        return sprintf('Status Code: %d, Response: %s', $this->getStatusCode(), $this->getJson());
    }
}