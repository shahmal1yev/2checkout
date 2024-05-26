<?php

namespace TwoCheckout\REST6\Interfaces\Requests\Product\PostProduct;

use TwoCheckout\REST6\Interfaces\Requests\RestRequestInterface;

interface CreateProductBasicInterface extends RestRequestInterface
{
    public function withAvangateId(int $avangateId): CreateProductBasicInterface;
    public function withCode(string $productCode): CreateProductBasicInterface;
    public function withExternalReference(string $externalReference): CreateProductBasicInterface;
    public function withType(string $productType): CreateProductBasicInterface;
    public function withName(string $productName): CreateProductBasicInterface;
    public function withVersion(string $productVersion): CreateProductBasicInterface;
    public function withGroup(array $group): CreateProductBasicInterface;
    public function withPurchaseMultipleUnits(bool $purchaseMultipleUnits): CreateProductBasicInterface;
    public function withShippingClass(array $shippingClass): CreateProductBasicInterface;
    public function withGiftOption(bool $giftOption): CreateProductBasicInterface;
    public function withShortDescription(string $shortDescription): CreateProductBasicInterface;
    public function withLongDescription(string $longDescription): CreateProductBasicInterface;
    public function withSystemRequirements(string $requestSystemRequirements): CreateProductBasicInterface;
    public function withCategory($category = false): CreateProductBasicInterface;
    public function withPlatforms(array $platforms): CreateProductBasicInterface;
    public function withTrialUrl(string $trialUrl): CreateProductBasicInterface;
    public function withTrialDescription(string $trialDescription): CreateProductBasicInterface;
    public function withTangible(bool $tangible): CreateProductBasicInterface;
    public function withEnabled(bool $enabled): CreateProductBasicInterface;
    public function withAdditionalFields(array $additionalFields): CreateProductBasicInterface;
    public function withTranslations(array $translations): CreateProductBasicInterface;
    public function withPricingConfigurations(array $pricingConfigurations): CreateProductBasicInterface;
    public function withPrices(array $prices): CreateProductBasicInterface;
}