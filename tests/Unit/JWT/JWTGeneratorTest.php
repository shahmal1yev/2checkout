<?php

namespace Unit\JWT;

use DateTime;
use TwoCheckout\JWT\Algorithms\HS512;
use TwoCheckout\JWT\JWTGenerator;
use PHPUnit\Framework\TestCase;
use TwoCheckout\JWT\TwoCheckout\Adapters\TwoCheckoutJWTConfigAdapter;
use TwoCheckout\JWT\TwoCheckout\TwoCheckoutJWTConfig;

class JWTGeneratorTest extends TestCase
{
    public function setUp(): void
    {

    }

    public function testGenerateJWT()
    {
        $twoCheckoutJWTConfig = (new TwoCheckoutJWTConfig())
            ->setAlgorithm(new HS512)
            ->setExpiration(new DateTime('+1 hour'))
            ->setMerchantID('255024088774');

        $jwtConfigAdapter = new TwoCheckoutJWTConfigAdapter($twoCheckoutJWTConfig);
        $generator = new JWTGenerator($jwtConfigAdapter);

        $signature = $generator->generate();

        $this->assertSame($signature, $generator->generate());
    }
}
