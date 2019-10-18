PHP API Client for Freshmail API V3

[Official API V3](https://freshmail.pl/dokumentacja-rest-api-v3/docs/messaging/emails/)

# Requirements

* PHP7.1 and above

# Installation

Update your composer.json and run `composer update`

``` json
{
    "require": {
        "freshmail/php-api-client": "^1.0"
    }
}
```

or execute

``` bash
composer require freshmail/php-api-client
```

# Usage

## Sent transactional email

```php

use \FreshMail\Api\Client\Service\Messaging\Mail;
use \FreshMail\Api\Client\Messaging\Mail\MailBag;

$token = 'MY_APP_TOKEN';
$mailService = new Mail($token);

$mail = new MailBag();
$mail->setFrom('from@address.com', 'Office');
$mail->setSubject('That\'s my awesome first mail!');
$mail->setHtml('<html><body><strong>Look!</strong> its working!</body></html>');
$mail->addRecipientTo('recipient email address');

$response = $mailService->send($mail);

```

## Handle with response object

```php
if ($response->isSuccess()) {
    $responseData = $response->getData();    
}
```

## Handle with raw [PSR-7 ResponseInterface](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface)

```php
$response->getPsr7Response();
```

## Send personalized email 

```php
use \FreshMail\Api\Client\Service\Messaging\Mail;
use \FreshMail\Api\Client\Messaging\Mail\MailBag;

$token = 'MY_APP_TOKEN';
$mailService = new Mail($token);

$mail = new MailBag();
$mail->setFrom('from@address.com', 'Office');
$mail->setSubject('Hello $$first_name$$! I\'v got promotion code for You!');
$mail->setHtml('<html><body>Your code is <strong>$$code$$</strong></body></html>');
$mail->addRecipientTo('recipient email address', [
    'first_name' => 'Joshua',
    'code' => 'CODE1234'
]);

$response = $mailService->send($mail);

```

## Send multiply emails 

You can send multiple emails by one request. It's much faster than sending one email by one request.
In one request You can send up to 100 emails.

```php
use \FreshMail\Api\Client\Service\Messaging\Mail;
use \FreshMail\Api\Client\Messaging\Mail\MailBag;

$token = 'MY_APP_TOKEN';
$mailService = new Mail($token);

$mail = new MailBag();
$mail->setFrom('from@address.com', 'Office');
$mail->setSubject('Hello $$first_name$$! I\'v got promotion code for You!');
$mail->setHtml('<html><body>Your code is <strong>$$code$$</strong></body></html>');

//first recipient
$mail->addRecipientTo('recipient email address', [
    'first_name' => 'Joshua',
    'code' => '10percentDISCOUNT'
]);

//second recipient
$mail->addRecipientTo('second recipient email address', [
    'first_name' => 'Donald',
    'code' => '25percentDISCOUNT'
]);

//third recipient
$mail->addRecipientTo('third recipient email address', [
    'first_name' => 'Abbie',
    'code' => 'FREEshippingDISCOUNT'
]);

$response = $mailService->send($mail);
```

## Send email from template

You can use FreshMail Templates mechanism to optimize requests to API. Additionally You can modify content of Your emails in FreshMail, not modifying the code of Your application.
```php
use \FreshMail\Api\Client\Service\Messaging\Mail;
use \FreshMail\Api\Client\Messaging\Mail\MailBag;

$token = 'MY_APP_TOKEN';
$mailService = new Mail($token);

$mail = new MailBag();
$mail->setFrom('from@address.com', 'Support');
$mail->setSubject('Hello, that\'s my email genereted by template!');
$mail->setTemplateHash('TEMPLATE_HASH');
$mail->addRecipientTo('recipient email address');

$response = $mailService->send($mail);
```

# Error handling
API throws exceptions for errors that occurred during requests and errors occurred before sending requests.

- If request is not sent to server because of wrong API usage, a `FreshMail\Api\Client\Exception\ApiUsageException` is thrown. This kind of exception means, for example, wrong parameters pass to some methods or passing both content and template in transactional mail, which means request won't be accepted, so API does not make any request.  
- If request is sent and some network issue occurred (DNS error, connection timeout, firewall blocking requests), a `FreshMail\Api\Client\Exception\RequestException` is thrown.
- If request is sent, a response is received but some server error occurred (500 level http code), a `FreshMail\Api\Client\Exception\ServerException` is thrown.
- If request is sent, a response is received but some client error occurred (400 level http code), a `FreshMail\Api\Client\Exception\ClientError` is thrown. This error message has `getRequest` and `getResponse` methods to receive raw Request nad Response object (implemented by PSR-7 `Psr\Http\Message\RequestInterface` and `Psr\Http\Message\ResponseInterface`)

```php
use FreshMail\Api\Client\Exception\ClientError;

try {
    $response = $mailService->send($mail);
} catch (ClientException $exception) {
    echo $exception->getRequest()->getBody();
    echo $exception->getResponse()->getBody();
}
```

`FreshMail\Api\Client\Exception\RequestException`, `FreshMail\Api\Client\Exception\ClientException` and `FreshMail\Api\Client\Exception\ServerException` extends from `FreshMail\Api\Client\Exception\TransferException`.
`FreshMail\Api\Client\Exception\TransferException` and `FreshMail\Api\Client\Exception\ApiUsageException` extends from `FreshMail\Api\Client\Exception\GeneralApiException`. All exceptions has methods `hasRequest`, `hasResponse`.

```php
use FreshMail\Api\Client\Exception\GeneralApiException;

try {
    $response = $mailService->send($mail);
} catch (GeneralApiException $exception) {
    $error = $exception->getMessage();
    if ($exception->hasRequest()) {
        $request = (string) $exception->getRequest()->getBody();
    }
    
    if ($exception->hasResponse()) {
        $response = (string) $exception->getResponse()->getBody();
    }
}
```

# Proxy setup
If You need to configure proxy You can use a custom `GuzzleHttp\Client` object.

```php
use \FreshMail\Api\Client\Service\Messaging\Mail;

$client = new \GuzzleHttp\Client(
    [
        'proxy' => 'my proxy url'
    ]
);

$token = 'MY_APP_TOKEN';
$mailService = new Mail($token);
$mailService->setGuzzleHttpClient($client);
```  


# Logging and debugging

If You need to log or debug Your request You can use 2 features.

## PSR-3 Logger Interface

You can use any library that implements [PSR-3](https://www.php-fig.org/psr/psr-3/) `Psr\Log\LoggerInterface`, example with Monolog below:
```php
use \FreshMail\Api\Client\Service\Messaging\Mail;

$logger = new \Monolog\Logger('myCustomLogger');
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stderr', \Monolog\Logger::DEBUG));

$token = 'MY_APP_TOKEN';
$mailService = new Mail($token);
$mailService->setLogger($logger);
```  

## Logging by GuzzleHttp Client

To make request API use `GuzzleHttp\Client` object. If You want You can configure it in any way You want, especially You can enable logging in Client.

```php
use \FreshMail\Api\Client\Service\Messaging\Mail;

$stack = \GuzzleHttp\HandlerStack::create();
$stack->push(
    \GuzzleHttp\Middleware::log(
        new \Monolog\Logger('Logger'),
        new \GuzzleHttp\MessageFormatter('{req_body} - {res_body}')
    )
);

$client = new \GuzzleHttp\Client(
    [
        'handler' => $stack,
    ]
);

$token = 'MY_APP_TOKEN';
$mailService = new Mail($token);
$mailService->setGuzzleHttpClient($client);
```
