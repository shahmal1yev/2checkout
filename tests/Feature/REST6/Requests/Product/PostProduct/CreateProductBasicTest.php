<?php

namespace Tests\Feature\REST6\Requests\Product\PostProduct;

use Exception;
use PHPUnit\Framework\TestCase;
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
        $request = new CreateProductBasic(new JSONContentHandler);

        $request->withAuthentication("255024088774", "(q?B%cmMlA3V|[t_*zp5")
            ->withName("Product Name")
            ->withCode("ProductUniqueCode")
            ->withPricingConfigurations([
                'Prices' => [
                    'Amount' => 1.4,
                    'Currency' => 'USD',
                    'MaxQuantity' => '1',
                    'MinQuantity' => '1',
                    'OptionCodes' => []
                ]
            ]);

        $client = new Client($request, new CurlResponseHandler);

        $response = $client->execute(EnvironmentEnum::PRODUCTION);

        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals((object) ['result' => true], $response->body);
    }
}