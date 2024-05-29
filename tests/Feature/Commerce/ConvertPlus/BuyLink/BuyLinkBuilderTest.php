<?php

namespace Tests\Feature\Commerce\ConvertPlus\BuyLink;

use DateTime;
use PHPUnit\Framework\TestCase;
use TwoCheckout\Commerce\Enum\CurrencyEnum;
use TwoCheckout\Commerce\Interfaces\ConvertPlus\BuyLink\BuyLinkBuilderInterface;
use TwoCheckout\ConvertPlus\BuyLink\BuyLinkBuilder;
use TwoCheckout\ConvertPlus\Entities\Product;
use TwoCheckout\JWT\Algorithms\HS512;
use TwoCheckout\JWT\JWTGenerator;
use TwoCheckout\JWT\TwoCheckout\Adapters\TwoCheckoutJWTConfigAdapter;
use TwoCheckout\JWT\TwoCheckout\TwoCheckoutJWTConfig;

class BuyLinkBuilderTest extends TestCase
{
    protected string $token;

    public function setUp(): void
    {
        $twoCheckoutJWTConfig = (new TwoCheckoutJWTConfig())
            ->setAlgorithm(new HS512)
            ->setExpiration(new DateTime('+1 hour'))
            ->setMerchantID('255024088774');

        $jwtConfigAdapter = new TwoCheckoutJWTConfigAdapter($twoCheckoutJWTConfig);
        $generator = new JWTGenerator($jwtConfigAdapter);

        $this->token = $generator->generate();

        $this->assertSame($this->token, $generator->generate());
    }

    public function testBuild()
    {
        $builder = new BuyLinkBuilder($this->token);

        $product1 = new Product();
        $product2 = new Product();

        $product1->setName('Product 1')
            ->setPrice(10)
            ->setQuantity(1);

        $product2->setName('Product 2')
            ->setPrice(20)
            ->setQuantity(3);

        $linkBuilder = $builder->setMerchant('255024088774')
            ->setCurrency(CurrencyEnum::EUR)
            ->setDynamic(true)
            ->enableTestMode()
            ->addProduct($product1)
            ->addProduct($product2)
            ->build();

        $this->assertInstanceOf(BuyLinkBuilderInterface::class, $linkBuilder);
    }
}
