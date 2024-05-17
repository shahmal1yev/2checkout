<?php

namespace Tests\Unit\REST6;

use TwoCheckout\Data\ContentHandlers\JSONContentHandler;
use TwoCheckout\HTTP\CurlResponseHandler;
use TwoCheckout\REST6\Client;
use TwoCheckout\REST6\Requests\Product\GetProduct;

test('execute request', function () {
    $request = new GetProduct(new JSONContentHandler);
    $request->setAuthenticationHeader("255024088774", "(q?B%cmMlA3V|[t_*zp5");
    $client = new Client($request, new CurlResponseHandler);

    $response = $client->execute("production");

    expect($response)
        ->toBeObject()
        ->toHaveKey("statusCode")
        ->toHaveKey("body")
        ->toHaveKey("headers")
        ->and($response->headers)
        ->toHaveKey("content-type", "application/json")
        ->and($response->body)
        ->toHaveKey("Items"); # products
});