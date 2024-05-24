<?php

namespace Tests\Feature\REST6\Requests\Product\PostProduct;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TwoCheckout\Data\ContentHandlers\JSONContentHandler;
use TwoCheckout\Exceptions\HTTP\CurlException;
use TwoCheckout\HTTP\CurlResponseHandler;
use TwoCheckout\REST6\Client;
use TwoCheckout\REST6\Enums\EnvironmentEnum;
use TwoCheckout\REST6\Requests\Product\PostProduct\CreateProductBasic;

class CreateProductBasicTest extends TestCase
{
    /**
     * @throws CurlException
     * @throws Exception
     */
    public function testCreateProductBasic()
    {
        $contentHandler = new JSONContentHandler;

        $request = new CreateProductBasic($contentHandler);

        $request->withAuthentication("255024088774", "(q?B%cmMlA3V|[t_*zp5")
            ->withName("Product Name")
            ->withCode("Product Code 4444")
            ->withPricingConfigurations([
                'Prices' => [
                    'Amount' => 1.4,
                    'Currency' => 'EUR',
                    'MaxQuantity' => '1',
                    'MinQuantity' => '1',
                    'OptionCodes' => []
                ]
            ]);

        $client = new Client($request, new CurlResponseHandler);

        $response = $client->execute(EnvironmentEnum::PRODUCTION);

        $expected = (object) [
            'body' => (object) ['scalar' => true],
            'statusCode' => 201
        ];

        $this->assertEquals($expected->body, $response->body);
        $this->assertEquals($expected->statusCode, $response->statusCode);
    }
}