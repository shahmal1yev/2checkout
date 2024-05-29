<?php

namespace Tests\Feature\Commerce\ConvertPlus\BuyLink;

use DateTime;
use PHPUnit\Framework\TestCase;
use TwoCheckout\Commerce\Enum\CurrencyEnum;
use TwoCheckout\Commerce\Interfaces\ConvertPlus\BuyLink\BuyLinkBuilderInterface;
use TwoCheckout\ConvertPlus\BuyLink\BuyLinkBuilder;
use TwoCheckout\ConvertPlus\BuyLink\BuyLinkGenerator;
use TwoCheckout\ConvertPlus\Entities\Product;
use TwoCheckout\ConvertPlus\Enums\EnvironmentEnum;
use TwoCheckout\Exceptions\Data\InvalidEnvironmentException;
use TwoCheckout\Exceptions\HTTP\CurlException;
use TwoCheckout\HTTP\CurlResponseHandler;
use TwoCheckout\JWT\Algorithms\HS512;
use TwoCheckout\JWT\JWTGenerator;
use TwoCheckout\JWT\TwoCheckout\Adapters\TwoCheckoutJWTConfigAdapter;
use TwoCheckout\JWT\TwoCheckout\TwoCheckoutJWTConfig;

class BuyLinkGeneratorTest extends TestCase
{
    protected BuyLinkBuilderInterface $buyLinkBuilder;

    public function setUp(): void
    {
        $twoCheckoutJWTConfig = (new TwoCheckoutJWTConfig())
            ->setAlgorithm(new HS512)
            ->setExpiration(new DateTime('+1 hour'))
            ->setMerchantID('255024088774');

        $jwtConfigAdapter = new TwoCheckoutJWTConfigAdapter($twoCheckoutJWTConfig);
        $generator = new JWTGenerator($jwtConfigAdapter);

        $token = $generator->generate('E*$-48@Xfe?RdE4d$xstQ6xQmAS*s3JD7qZt2n$e-H$MBRUu3cSAFa@4d#Zj*?7m');

        $builder = new BuyLinkBuilder($token);

        $product1 = new Product();
        $product2 = new Product();

        $product1->setName('Prod1')
            ->setPrice(10)
            ->setQuantity(1);

        $product2->setName('Prod2')
            ->setPrice(20)
            ->setQuantity(3);

        $this->buyLinkBuilder = $builder->setMerchant('255024088774')
            ->setCurrency(CurrencyEnum::EUR)
            ->setDynamic(true)
            ->enableTestMode()
            ->addProduct($product1)
            ->addProduct($product2);
    }

    /**
     * @throws CurlException
     * @throws InvalidEnvironmentException
     */
    public function testBuyLinkGenerate()
    {
        $generator = new BuyLinkGenerator($this->buyLinkBuilder, new CurlResponseHandler);

        $response = $generator->execute(EnvironmentEnum::PRODUCTION);

        $this->assertIsObject($response);
        $this->assertIsString($response->body->link);

        $context = stream_context_create([
            'http' => [
                'ignore_errors' => true,
                'follow_location' => true,
//                'proxy' => 'tcp://127.0.0.1:9091',
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        file_get_contents($response->body->link, false, $context);

        $this->assertIsArray($http_response_header);

        $http_response_header_string = implode("\r\n", $http_response_header);
        $this->assertStringContainsString("200 OK", $http_response_header_string, "The response has not 200 status code");

        $this->assertStringContainsString("https://secure.2checkout.com/checkout/", $http_response_header_string, "The response has not checkout page URL");
    }
}
