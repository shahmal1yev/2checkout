<?php

namespace TwoCheckout\Commerce\Requests;

use InvalidArgumentException;
use TwoCheckout\Commerce\Builder\RequestBuilder;
use TwoCheckout\Commerce\Enum\CurrencyEnum;
use TwoCheckout\Commerce\Enum\EnvironmentEnum;
use TwoCheckout\Commerce\Enum\LanguageEnum;
use TwoCheckout\Commerce\Interfaces\Requests\BuyLinkInterface;
use TwoCheckout\Commerce\Interfaces\Requests\CommerceRequestInterface;
use TwoCheckout\Exceptions\Data\InvalidCurrencyException;
use TwoCheckout\Exceptions\Data\InvalidEnvironmentException;
use TwoCheckout\Exceptions\Data\InvalidQuantityException;
use TwoCheckout\Exceptions\Data\NegativePriceException;
use TwoCheckout\Exceptions\Data\NotValidPriceException;
use TwoCheckout\Helpers\Arr;
use TwoCheckout\HTTP\Request;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;

/**
 * Class BuyLink
 *
 * Represents a request to generate a buy link for purchasing products.
 *
 * @see https://verifone.cloud/docs/2checkout/Documentation/07Commerce/Checkout-links-and-options/Buy-Link-parameters Documentation
 */
class BuyLink extends RequestBuilder implements BuyLinkInterface
{
    /**
     * @var bool Indicates whether the request should redirect.
     */
    protected bool $redirect = true;

    /**
     * @var string The secret key used for generating the pHash.
     */
    protected string $secretKey;

    /**
     * @var array Required query parameters for the buy link.
     */
    protected array $requiredQueryParams;

    /**
     * @var array Query parameter names mapped to their respective keys.
     */
    protected array $queryParameterNames = [
        'products'              => 'PRODS',
        'prices'                => 'PRICES',
        'quantities'            => 'QTY',
        'language'              => 'LANG',
        'lockedExpressMethod'   => 'CART',
        'phash'                 => 'PHASH'
    ];

    /**
     * @var array The query parameters array.
     */
    protected array $queryParameters = [];

    /**
     * BuyLink constructor.
     *
     * @param string $secretKey The secret key used for generating the pHash.
     *
     * @see https://verifone.cloud/docs/2checkout/Documentation/07Commerce/Checkout-links-and-options/Buy-Link-parameters Documentation
     */
    public function __construct(string $secretKey)
    {
        parent::__construct();

        $this->secretKey = $secretKey;
        $this->setUri('/order/checkout.php');

        $this->requiredQueryParams = [
            Arr::get($this->queryParameterNames, 'products'),
            Arr::get($this->queryParameterNames, 'prices'),
            Arr::get($this->queryParameterNames, 'quantities'),
            Arr::get($this->queryParameterNames, 'phash'),
        ];
    }

    /**
     * Adds a product to the buy link with prices and quantity.
     *
     * @param string $productID The product ID.
     * @param array $prices The prices array where keys are currency codes and values are prices.
     * @param int $quantity The quantity of the product.
     * @return BuyLinkInterface
     * @throws NegativePriceException
     * @throws NotValidPriceException
     * @throws InvalidCurrencyException
     * @throws InvalidQuantityException
     */
    public function addProduct(string $productID, array $prices, int $quantity): BuyLinkInterface
    {
        $this->validatePrices($prices);
        $this->validateQuantity($quantity);

        $this->setProduct($productID);
        $this->setPrices($productID, $prices);
        $this->setQuantity($quantity);

        return $this;
    }

    /**
     * Sets the locked express method parameter to true.
     *
     * @return BuyLinkInterface
     */
    public function withLockedExpressMethod(): BuyLinkInterface
    {
        $lockedExpMethodQParamName = Arr::get($this->queryParameterNames, 'lockedExpressMethod');
        $this->setQueryParam($lockedExpMethodQParamName, 0);

        return $this;
    }

    /**
     * Sets the locked express method parameter to false.
     *
     * @return BuyLinkInterface
     */
    public function withoutLockedExpressMethod(): BuyLinkInterface
    {
        $lockedExpMethodQParamName = Arr::get($this->queryParameterNames, 'lockedExpressMethod');
        $this->setQueryParam($lockedExpMethodQParamName, 1);

        return $this;
    }

    /**
     * Sets the language parameter for the buy link.
     *
     * @param string $language The language code.
     * @return BuyLinkInterface
     * @throws InvalidArgumentException When the language code is invalid.
     */
    public function withLanguage(string $language): BuyLinkInterface
    {
        if (!LanguageEnum::isValid($language))
            throw new InvalidArgumentException("'$language' is not a valid language code");

        $languageQueryParamName = Arr::get($this->queryParameterNames, 'language');
        $this->setQueryParam($languageQueryParamName, $language);

        return $this;
    }

    /**
     * Builds the buy link with all the necessary parameters.
     *
     * @param string $environment
     *
     * @return string BuyLink
     *
     * @throws InvalidEnvironmentException
     */
    public function build(string $environment): string
    {
        $this->validateEnvironment($environment);
        $this->prepareForBuilding();
        $this->preparePHash();

        foreach ($this->queryParameters as $name => $value)
            $this->setQueryParam($name, $value);

        return sprintf("%s%s?%s", $environment, $this->getUri(), $this->getQueryParams());
    }

    /**
     * @param string $environment
     *
     * @return void
     *
     * @throws InvalidEnvironmentException
     */
    protected function validateEnvironment(string $environment): void
    {
        if (! EnvironmentEnum::isValid($environment))
            throw new InvalidEnvironmentException("'$environment' is not a valid environment.");
    }

    /**
     * Sets the product ID in the query parameters.
     *
     * @param string $productID The product ID.
     * @return BuyLinkInterface
     */
    protected function setProduct(string $productID): BuyLinkInterface
    {
        $productQueryParameterName = Arr::get($this->queryParameterNames, 'products');
        $availableProducts = Arr::get($this->queryParameters, $productQueryParameterName, []);
        $this->queryParameters[$productQueryParameterName] = array_merge($availableProducts, [$productID]);

        return $this;
    }

    /**
     * Sets the prices for a product in the query parameters.
     *
     * @param string $productID The product ID.
     * @param array $prices The prices array where keys are currency codes and values are prices.
     * @return BuyLinkInterface
     */
    protected function setPrices(string $productID, array $prices): BuyLinkInterface
    {
        $pricesQueryParamName = Arr::get($this->queryParameterNames, 'prices');
        foreach ($prices as $currency => $price)
            $this->queryParameters[$pricesQueryParamName . $productID][$currency] = round(floatval($price), 2);

        return $this;
    }

    /**
     * Sets the quantity of a product in the query parameters.
     *
     * @param int $quantity The quantity of the product.
     * @return BuyLinkInterface
     */
    protected function setQuantity(int $quantity): BuyLinkInterface
    {
        $quantityQueryParamName = Arr::get($this->queryParameterNames, 'quantities');
        $availableQuantities = Arr::get($this->queryParameters, $quantityQueryParamName, []);
        $newQuantities = array_merge($availableQuantities, [$quantity]);
        $this->queryParameters[$quantityQueryParamName] = $newQuantities;

        return $this;
    }

    /**
     * Validates the quantity of a product.
     *
     * @param int $quantity The quantity of the product.
     * @throws InvalidQuantityException If the quantity is less than 1.
     */
    protected function validateQuantity(int $quantity): void
    {
        if ($quantity < 1)
            throw new InvalidQuantityException("'$quantity' quantity must be greater than 0");
    }

    /**
     * Validates the prices array.
     *
     * @param array $prices The prices array where keys are currency codes and values are prices.
     * @throws NotValidPriceException
     * @throws NegativePriceException
     * @throws InvalidCurrencyException
     */
    protected function validatePrices(array $prices): void
    {
        foreach ($prices as $currency => $price) {
            $this->validatePrice($price);
            $this->validateCurrency($currency);
        }
    }

    /**
     * Validates a price value.
     *
     * @param mixed $price The price value.
     * @throws NotValidPriceException
     * @throws NegativePriceException
     */
    protected function validatePrice($price): void
    {
        if (!(is_int($price) || is_float($price)))
            throw new NotValidPriceException("'$price' is not a valid price. It must be an int or float");

        if ($price < 0)
            throw new NegativePriceException("'$price' is not a valid price. It must be a positive number");
    }

    /**
     * Validates a currency code.
     *
     * @param string $currency The currency code.
     * @throws InvalidCurrencyException If the currency code is not valid.
     */
    protected function validateCurrency(string $currency): void
    {
        if (!CurrencyEnum::isValid($currency))
            throw new InvalidCurrencyException(
                "'$currency' is not a valid currency. Use " . CurrencyEnum::class . " for setting it."
            );
    }

    /**
     * Prepares the query parameters for building the buy link.
     */
    protected function prepareForBuilding(): void
    {
        $this->prepareProducts();
        $this->prepareQuantities();
    }

    /**
     * Prepares the product IDs for the buy link.
     */
    protected function prepareProducts(): void
    {
        $productsQueryParamName = Arr::get($this->queryParameterNames, 'products', 'PRODS');
        $products = Arr::get($this->queryParameters, $productsQueryParamName, []);
        $this->queryParameters[$productsQueryParamName] = implode(",", $products);
    }

    /**
     * Prepares the quantities for the buy link.
     */
    protected function prepareQuantities(): void
    {
        $quantitiesQueryParamName = Arr::get($this->queryParameterNames, 'quantities', 'QTY');
        $quantities = Arr::get($this->queryParameters, $quantitiesQueryParamName, []);
        $this->queryParameters[$quantitiesQueryParamName] = implode(",", $quantities);
    }

    /**
     * Prepares the pHash for the buy link.
     */
    protected function preparePHash(): void
    {
        $pHash = $this->generatePHash();
        $pHashQueryParamName = Arr::get($this->queryParameterNames, 'phash');
        $this->queryParameters[$pHashQueryParamName] = $pHash;
    }

    /**
     * Generates the pHash using HMAC-SHA3-256.
     *
     * @return string The generated pHash.
     */
    protected function generatePHash(): string
    {
        $asUrlEncoded = http_build_query($this->queryParameters);
        $lengthOf = strlen($asUrlEncoded);
        $pHashContent = $lengthOf . $asUrlEncoded;
        $hash = hash_hmac('sha3-256', $pHashContent, $this->secretKey);

        return $hash;
    }
}
