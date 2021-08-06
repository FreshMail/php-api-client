<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Service;

use FreshMail\Api\Client\Response\HttpResponse;
use FreshMail\Api\Client\FreshMailApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class RequestExecutor
{
    const
        SCHEME = 'https',
        HOST = 'api.freshmail.com',
        VERSION = 'v3';

    /**
     * @var string
     */
    private $bearerToken;

    /**
     * @var Client
     */
    private $guzzle;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RequestExecutor constructor.
     * @param $bearerToken
     * @param $guzzle
     * @param $logger
     */
    public function __construct(string $bearerToken, Client $guzzle, LoggerInterface $logger)
    {
        $this->bearerToken = $bearerToken;
        $this->guzzle = $guzzle;
        $this->logger = $logger;
    }

    /**
     * @param string $uri
     * @param \JsonSerializable $data
     * @return Response
     */
    public function post(string $uri, \JsonSerializable $data): HttpResponse
    {
        try {
            $response = $this->guzzle->request('POST', $uri, $this->getRequestOptions($data));
            $this->logger->debug(Psr7\Message::toString($response));
            return new HttpResponse($response);
        } catch (ClientException $exception) {
            $this->logger->error(sprintf('Request: %s, Response: %s', Psr7\Message::toString($exception->getRequest()), Psr7\Message::toString($exception->getResponse())));
            throw new \FreshMail\Api\Client\Exception\ClientException($exception->getMessage(), $exception->getRequest(), $exception->getResponse());
        } catch (ServerException $exception) {
            throw new \FreshMail\Api\Client\Exception\ServerException($exception->getMessage());
        } catch (TransferException $exception) {
            throw new \FreshMail\Api\Client\Exception\RequestException($exception->getMessage());
        }
    }

    /**
     * @return array
     */
    private function getRequestOptions(\JsonSerializable $data): array
    {
        return [
            'base_uri' => sprintf('%s://%s/%s/', self::SCHEME, self::HOST, self::VERSION),
            RequestOptions::BODY => json_encode($data),
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'User-Agent' => $this->createUserAgent()
            ]
        ];
    }

    /**
     * @return string
     */
    private function createUserAgent(): string
    {
        return
             sprintf(
        'freshmail/php-api-client:%s;guzzle:%s;php:%s;interface:%s',
        FreshMailApiClient::VERSION,
        ClientInterface::MAJOR_VERSION,
        PHP_VERSION,
        php_sapi_name());
    }

}