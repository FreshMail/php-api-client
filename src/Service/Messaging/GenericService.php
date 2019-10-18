<?php declare(strict_types=1);

namespace FreshMail\Api\Client\Service\Messaging;

use FreshMail\Api\Client\Service\RequestExecutor;

class GenericService
{
    /**
     * @var RequestExecutor
     */
    protected $requestExecutor;

    public function __construct(RequestExecutor $requestExecutor)
    {
        $this->requestExecutor = $requestExecutor;
    }
}