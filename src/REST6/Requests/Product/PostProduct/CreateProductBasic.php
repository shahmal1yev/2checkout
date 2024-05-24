<?php

namespace TwoCheckout\REST6\Requests\Product\PostProduct;

use InvalidArgumentException;
use TwoCheckout\Helpers\Arr;
use TwoCheckout\HTTP\Request;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;
use TwoCheckout\REST6\Enums\Requests\Product\PostProduct\ProductTypeEnum;
use TwoCheckout\REST6\Exceptions\RequiredOptionArgumentMissingException;
use TwoCheckout\REST6\Interfaces\HTTP\RestRequestInterface;
use TwoCheckout\REST6\Interfaces\Requests\Product\PostProduct\CreateProductBasicInterface;
use TwoCheckout\REST6\Traits\WithAuthentication;

class CreateProductBasic extends Request implements CreateProductBasicInterface
{
    use WithAuthentication;

    protected array $missingRequiredFields = [
        "ProductName",
        "ProductCode",
        "PricingConfigurations"
    ];

    public function __construct(ContentHandlerInterface $contentHandler)
    {
        parent::__construct($contentHandler);

        $this->setMethod("POST")
            ->setUri("/products")
            ->setHeader("Accept", "application/json")
            ->setHeader("Content-Type", "application/json");
    }

    public function withAvangateId(int $avangateId): CreateProductBasicInterface
    {
        $this->setField("AvangateId", $avangateId);

        return $this;
    }

    public function withCode(string $productCode): CreateProductBasicInterface
    {
        $fieldName = "ProductCode";
        $this->setField($fieldName, $productCode);

        Arr::forget($this->missingRequiredFields, $fieldName);

        return $this;
    }

    public function withExternalReference(string $externalReference): CreateProductBasicInterface
    {
        $this->setField("ExternalReference", $externalReference);

        return $this;
    }

    public function withType(string $productType = 'REGULAR'): CreateProductBasicInterface
    {
        if (! ProductTypeEnum::isValid($productType))
            throw new InvalidArgumentException("'$productType' is not a valid product type.'");

        $this->setField("ProductType", $productType);

        return $this;
    }

    public function withName(string $productName): CreateProductBasicInterface
    {
        $fieldName = "ProductName";
        $this->setField($fieldName, $productName);
        Arr::forget($this->missingRequiredFields, $fieldName);

        return $this;
    }

    public function withVersion(string $productVersion): CreateProductBasicInterface
    {
        $this->setField("ProductVersion", $productVersion);

        return $this;
    }

    public function withGroup(array $group): CreateProductBasicInterface
    {
        $allowedParameters = [
            'Code' => true,
            'Description' => false,
            'Name' => false,
            'TemplateName' => false
        ];

        $requiredParameters = array_filter($allowedParameters, function ($param) {
            return (!$param);
        });

        $missingParams = array_diff(
            array_keys($requiredParameters),
            array_keys($group)
        );

        if (!empty($missingParams))
            throw new InvalidArgumentException("'\$group' has missing required parameters: ", implode(", ", $missingParams));

        $this->setField("ProductGroup", $group);

        return $this;
    }

    public function withPurchaseMultipleUnits(bool $purchaseMultipleUnits = true): CreateProductBasicInterface
    {
        $this->setField("PurchaseMultipleUnits", $purchaseMultipleUnits);

        return $this;
    }

    /**
     * @throws RequiredOptionArgumentMissingException
     */
    public function withShippingClass(array $shippingClass): CreateProductBasicInterface
    {
        $fieldName = "ShippingClass";

        $arguments = [
            'Amount'    => ['nullable' => false,    'type' => 'string'],
            'ApplyTo'   => ['nullable' => true,     'type' => 'string'],
            'Currency'  => ['nullable' => false,    'type' => 'string'],
            'Name'      => ['nullable' => false,    'type' => 'string']
        ];

        $requiredParameters = array_filter($arguments, fn($param) => !($param['nullable']));
        $missingRequiredParameters = array_diff(
            array_keys($requiredParameters),
            array_keys($shippingClass)
        );

        if (! empty($missingRequiredParameters))
            throw new RequiredOptionArgumentMissingException(
                "'$fieldName' has missing required parameters: ", implode(", ", $missingRequiredParameters)
            );

        foreach($shippingClass as $key => $param)
        {
            $validType = Arr::get($arguments, "$key.type", false);

            if ($validType === false)
                continue;

            if (gettype($param) !== $validType)
                throw new InvalidArgumentException(
                    "'$param' is not a valid type for '$key'. That is must be of type $validType."
                );
        }

        $this->setField($fieldName, $shippingClass);

        return $this;
    }

    public function withGiftOption(bool $giftOption = false): CreateProductBasicInterface
    {
        $this->setField("GiftOption", $giftOption);

        return $this;
    }

    public function withShortDescription(string $shortDescription = "Product's short description"): CreateProductBasicInterface
    {
        $this->setField("ShortDescription", $shortDescription);

        return $this;
    }

    public function withLongDescription(string $longDescription = "Product's long description"): CreateProductBasicInterface
    {
        $this->setField("LongDescription", $longDescription);

        return $this;
    }

    public function withSystemRequirements(string $requestSystemRequirements = "System requirements"): CreateProductBasicInterface
    {
        $this->setField("SystemRequirements", $requestSystemRequirements);

        return $this;
    }

    public function withCategory($category = false): CreateProductBasicInterface
    {
        if (! (is_bool($category) || is_string($category)))
            throw new InvalidArgumentException("'\$category' must be a string or boolean.");

        $this->setField("ProductCategory", $category);

        return $this;
    }

    public function withPlatforms(array $platforms): CreateProductBasicInterface
    {
        $arguments = [
            "Category" => 'string',
            "IdPlatform" => 'string',
            "PlatformName" => 'string'
        ];

        $requiredArguments = array_keys($arguments);
        $missingRequiredArguments = array_diff(
            $requiredArguments,
            array_keys($platforms)
        );

        if (! empty($requiredArguments))
            throw new InvalidArgumentException(
                "'\$platforms' has missing required arguments: ", implode(", ", $missingRequiredArguments)
            );

        foreach($platforms as $key => $param)
        {
            $validType = Arr::get($arguments, $key, false);

            if ($validType === false)
                continue;

            if (gettype($param) !== $validType)
                throw new InvalidArgumentException(
                    "'$param' is not a valid type for '$key'. That is must be of type $validType."
                );
        }

        $this->setField("Platforms", $platforms);

        return $this;
    }

    public function withTrialUrl(string $trialUrl): CreateProductBasicInterface
    {
        $filedName = "TrialUrl";

        if (filter_var($trialUrl, FILTER_VALIDATE_URL) === false)
            throw new InvalidArgumentException("'$trialUrl' is not a valid URL for '$filedName'");

        $this->setField($filedName, $trialUrl);

        return $this;
    }

    public function withTrialDescription(string $trialDescription): CreateProductBasicInterface
    {
        $this->setField("TrialDescription", $trialDescription);

        return $this;
    }

    public function withTangible(bool $tangible): CreateProductBasicInterface
    {
        $this->setField("Tangible", $tangible);

        return $this;
    }

    public function withEnabled(bool $enabled = false): CreateProductBasicInterface
    {
        $this->setField("Enabled", $enabled);

        return $this;
    }

    public function withAdditionalFields(array $additionalFields): CreateProductBasicInterface
    {
        $fieldName = 'AdditionalFields';

        $arguments = [
            'Code' => 'string',
            'Enabled' => 'boolean',
            'Label' => 'string',
            'Required' => 'boolean',
            'URLParameter' => 'string'
        ];

        $requiredArguments = array_keys($arguments);
        $missingRequiredArguments = array_diff(
            $requiredArguments,
            array_keys($additionalFields)
        );

        if (!empty($missingRequiredArguments))
            throw new InvalidArgumentException(
                "'$fieldName' has missing required arguments: ", implode(", ", $missingRequiredArguments)
            );

        $this->setField($fieldName, $additionalFields);

        return $this;
    }

    public function withTranslations(array $translations): CreateProductBasicInterface
    {
        $fieldName = 'Translations';

        $arguments = [
            'Description' => ['nullable' => false, 'type' => 'string'],
            'Language' => ['nullable' => false, 'type' => 'string'],
            'LongDescription' => ['nullable' => false, 'type' => 'string'],
            'Name' => ['nullable' => false, 'type' => 'string'],
            'SystemRequirements' => ['nullable' => true, 'type' => 'string'],
            'TrialDescription' => ['nullable' => true, 'type' => 'string'],
            'TrialUrl' => ['nullable' => true, 'type' => 'string'],
        ];

        $requiredArguments = array_filter($arguments, fn($param) => !($param['nullable']));
        $missingRequiredArguments = array_diff(
            array_keys($requiredArguments),
            array_keys($translations)
        );

        if (!empty($missingRequiredArguments))
            throw new InvalidArgumentException(
                "'$fieldName' has missing required arguments: ", implode(", ", $missingRequiredArguments)
            );

        foreach($translations as $key => $param)
        {
            $validType = Arr::get($arguments, $key, false);

            if ($validType === false)
                continue;

            if (gettype($param) !== $validType)
                throw new InvalidArgumentException(
                    "'$param' is not a valid type for '$key'. That is must be of type $validType."
                );
        }

        $this->setField($fieldName, $translations);

        return $this;
    }

    public function withPricingConfigurations(array $pricingConfigurations): CreateProductBasicInterface
    {
        $fieldName = "PricingConfigurations";
        $arguments = [
            'Code' => ['nullable' => false, 'type' => 'string'],
            'Default' => ['nullable' => false, 'type' => 'bool'],
            'DefaultCurrency' => ['nullable' => false, 'type' => 'string'],
            'Name' => ['nullable' => false, 'type' => 'string'],
            'PriceOptions' => ['nullable' => false, 'type' => 'array'],
            'PriceType' => ['nullable' => false, 'type' => 'string'],
            'Prices'  => ['nullable' => false, 'type' => 'array'],
            'PricingSchema' => ['nullable' => false, 'type' => 'string'],
        ];

        $requiredArguments = array_filter($arguments, fn($param) => !($param['nullable']));
        $missingRequiredArguments = array_diff(
            array_keys($requiredArguments),
            array_keys($pricingConfigurations)
        );

        if (!empty($missingRequiredArguments))
            throw new InvalidArgumentException(
                "'$fieldName' has missing required arguments: ", implode(", ", $missingRequiredArguments)
            );

        foreach($pricingConfigurations as $key => $param)
        {
            $validType = Arr::get($arguments, $key, false);

            if ($validType === false)
                continue;

            if (gettype($param) !== $validType)
                throw new InvalidArgumentException(
                    "'$param' is not a valid type for '$key'. That is must be of type $validType"
                );
        }

        $this->setField($fieldName, $pricingConfigurations);

        return $this;
    }

    public function withPrices(array $prices): CreateProductBasicInterface
    {
        $fieldName = 'Prices';

        $arguments = [
            'Amount' => ['nullable' => false, 'type' => 'int|float'],
            'Currency' => ['nullable' => false, 'type' => 'string'],
            'MaxQuantity' => ['nullable' => false, 'type' => 'string'],
            'MinQuantity' => ['nullable' => false, 'type' => 'string'],
            'OptionCodes' => ['nullable' => false, 'type' => 'array'],
        ];

        $requiredArguments = array_filter($arguments, fn($param) => !($param['nullable']));
        $missingRequiredArguments = array_diff(
            array_keys($requiredArguments),
            array_keys($prices)
        );

        if (!empty($missingRequiredArguments))
            throw new InvalidArgumentException(
                "'$fieldName' has missing required arguments: ", implode(", ", $missingRequiredArguments)
            );

        foreach($prices as $key => $param)
        {
            $validTypes = Arr::get($arguments, $key, false);

            if ($validTypes === false)
                continue;

            $validTypes = explode('|', $validTypes);

            $typeOfData = gettype($param);
            $isAllowedType = in_array($typeOfData, $validTypes);

            if ($isAllowedType === false)
                throw new InvalidArgumentException(
                    "'$param' is not a valid type for '$key'. That is must be of type the following types: " . implode(", ", $validTypes)
                );
        }

        $this->setField($fieldName, $prices);

        return $this;
    }

    /**
     * @throws RequiredOptionArgumentMissingException
     */
    public function build(): RestRequestInterface
    {
        if (! empty($this->missingRequiredFields))
            throw new RequiredOptionArgumentMissingException(
                self::class . " has missing required fields: ", implode(", ", $this->missingRequiredFields)
            );

        $authHeaderName = "X-Avangate-Authentication";

        if (! $this->headerExists($authHeaderName))
            throw new RequiredOptionArgumentMissingException(
                self::class . " has missing required headers: $authHeaderName"
            );

        return $this;
    }
}