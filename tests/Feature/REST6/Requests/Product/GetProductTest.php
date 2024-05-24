<?php

namespace Tests\Feature\REST6\Requests\Product;

use Exception;
use TwoCheckout\Data\ContentHandlers\JSONContentHandler;
use TwoCheckout\Helpers\Arr;
use TwoCheckout\HTTP\CurlResponseHandler;
use TwoCheckout\REST6\Client;
use TwoCheckout\REST6\Enums\EnvironmentEnum;
use TwoCheckout\REST6\Requests\Product\GetProduct;
use PHPUnit\Framework\TestCase;

class GetProductTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetProduct()
    {
        $request = new GetProduct(new JSONContentHandler);

        $request->withAuthentication("255024088774", "(q?B%cmMlA3V|[t_*zp5")
            ->withName("Product Name");

        $client = new Client($request, new CurlResponseHandler);

        $response = $client->execute(EnvironmentEnum::PRODUCTION);

        $this->assertEquals(200, $response->statusCode);

        $responseAsArray = json_decode(json_encode($response), true);
        $productNames = array_column(Arr::get($responseAsArray, 'body.Items'), 'ProductName');
        $filteredNames = array_filter($productNames, fn ($productName) => $productName != "Product Name");

        $this->assertEmpty($filteredNames);
    }
}
