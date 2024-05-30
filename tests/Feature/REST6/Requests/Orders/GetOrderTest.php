<?php

namespace Tests\Feature\REST6\Requests\Orders;

use DateTime;
use Exception;
use TwoCheckout\Commerce\Enum\CurrencyEnum;
use TwoCheckout\ConvertPlus\BuyLink\BuyLinkBuilder;
use TwoCheckout\ConvertPlus\BuyLink\BuyLinkGenerator;
use TwoCheckout\ConvertPlus\Entities\Product;
use TwoCheckout\ConvertPlus\Enums\EnvironmentEnum;
use TwoCheckout\Data\ContentHandlers\JSONContentHandler;
use TwoCheckout\Exceptions\HTTP\CurlException;
use TwoCheckout\HTTP\CurlResponseHandler;
use TwoCheckout\JWT\Algorithms\HS512;
use TwoCheckout\JWT\JWTGenerator;
use TwoCheckout\JWT\TwoCheckout\Adapters\TwoCheckoutJWTConfigAdapter;
use TwoCheckout\JWT\TwoCheckout\TwoCheckoutJWTConfig;
use TwoCheckout\REST6\Client;
use TwoCheckout\REST6\Requests\Orders\GetOrder;
use PHPUnit\Framework\TestCase;

class GetOrderTest extends TestCase
{
    protected string $refNo = '235145304';

    /**
     * @throws CurlException
     * @throws Exception
     */
    public function testGetOrder()
    {
        $request = (new GetOrder(new JSONContentHandler))
            ->withAuthentication("255024088774", "(q?B%cmMlA3V|[t_*zp5")
            ->withExternalReference('235145304');

        $response = (new Client($request, new CurlResponseHandler))
                ->execute(\TwoCheckout\REST6\Enums\EnvironmentEnum::PRODUCTION);

        $this->assertEquals(200, $response->statusCode);
    }
}
