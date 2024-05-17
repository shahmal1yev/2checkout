<?php

namespace TwoCheckout\HTTP;

use TwoCheckout\Exceptions\HTTP\HTTPResponseException;
use TwoCheckout\Interfaces\HTTP\CurlResponseHandlerInterface;

class CurlResponseHandler implements CurlResponseHandlerInterface
{
    /**
     * @throws HttpResponseException
     */
    public function handle(int $statusCode, object $headers, object $body): object
    {
        $errMessage = $body->message ?? 'Unknown error';

        if (! ($statusCode < 200 || $statusCode > 299))
            return (object) [
                'statusCode' => $statusCode,
                'headers' => $headers,
                'body' => $body
            ];

        throw new HTTPResponseException($errMessage, $statusCode);
    }
}