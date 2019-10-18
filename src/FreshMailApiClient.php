<?php declare(strict_types=1);

namespace FreshMail\Api\Client;

use FreshMail\Api\Client\Factory\MonologFactory;
use FreshMail\Api\Client\Service\RequestExecutor;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

abstract class FreshMailApiClient
{
    const VERSION = '0.1.0';

    /**
     * @var string
     */
    private $bearerToken;

    /**
     * @var Client
     */
    private $guzzle;

    /**
     * @var \Monolog\Logger|LoggerInterface
     */
    private $logger;

    /**
     * @var RequestExecutor
     */
    protected $requestExecutor;

    /**
     * FreshMailApiClient constructor.
     * @param $bearerToken
     * @param $guzzle
     */
    public function __construct(string $bearerToken)
    {
        $this->bearerToken = $bearerToken;
        $this->guzzle = new Client();
        $this->logger = MonologFactory::createInstance();

        $this->requestExecutor = new RequestExecutor($this->bearerToken, $this->guzzle, $this->logger);
    }

    /**
     * @param Client $guzzle
     */
    public function setGuzzleHttpClient(Client $guzzle)
    {
        $this->guzzle = $guzzle;
        $this->requestExecutor = new RequestExecutor($this->bearerToken, $this->guzzle, $this->logger);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->requestExecutor = new RequestExecutor($this->bearerToken, $this->guzzle, $this->logger);
    }
}