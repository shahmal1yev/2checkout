<?php

namespace TwoCheckout\Commerce\Requests;

use InvalidArgumentException;
use TwoCheckout\Commerce\Enum\LanguageEnum;
use TwoCheckout\Commerce\Interfaces\Requests\BuyLinkInterface;
use TwoCheckout\Commerce\Interfaces\Requests\CommerceRequestInterface;
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
class BuyLink extends Request implements BuyLinkInterface
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
     * @var string The name of the query parameter for products.
     */
    protected string $productsQueryParamName;

    /**
     * @var string The name of the query parameter for prices.
     */
    protected string $pricesQueryParamName;

    /**
     * @var string The name of the query parameter for quantities.
     */
    protected string $quantitiesQueryParamName;

    /**
     * @var string The name of the query parameter for pHash.
     */
    protected string $pHashQueryParamName;

    /**
     * @var string The name of the query parameter for language.
     */
    protected string $langQueryParamName;

    /**
     * @var string The name of the query parameter for locked express method.
     */
    protected string $lockedExpressQueryParamName;

    /**
     * @var array The query parameters array.
     */
    protected array $queryParameters;

    /**
     * BuyLink constructor.
     *
     * @param ContentHandlerInterface $contentHandler The content handler interface.
     * @param string $secretKey The secret key used for generating the pHash.
     */
    public function __construct(ContentHandlerInterface $contentHandler, string $secretKey)
    {
        parent::__construct($contentHandler);

        $this->secretKey = $secretKey;

        $this->langQueryParamName = 'LANG';
        $this->pHashQueryParamName = 'PHASH';
        $this->pricesQueryParamName = 'PRICES';
        $this->quantitiesQueryParamName = 'QTY';
        $this->productsQueryParamName = 'PRODS';
        $this->lockedExpressQueryParamName = 'CART';

        $this->queryParameters = [
            $this->lockedExpressQueryParamName => '',
            $this->quantitiesQueryParamName => '',
            $this->productsQueryParamName => '',
            $this->pricesQueryParamName => '',
            $this->pHashQueryParamName => '',
            $this->langQueryParamName => '',
        ];

        $this->setUri('/order/checkout');
    }

    /**
     * Add a product to the buy link with the specified ID, price, and quantity.
     *
     * @param int $productID The ID of the product.
     * @param numeric $price The price of the product.
     * @param int $quantity The quantity of the product.
     * @return BuyLinkInterface
     * @throws InvalidArgumentException When the price or quantity is invalid.
     */
    public function addProduct(int $productID, $price, int $quantity): BuyLinkInterface
    {
        if (! (is_int($price) || is_float($price)))
            throw new InvalidArgumentException("'\$price' must be numeric");

        if ($price < 0)
            throw new InvalidArgumentException("'\$price' cannot be negative");

        if ($quantity < 0)
            throw new InvalidArgumentException("'\$quantity' cannot be negative");

        $price = round(floatval($price), 2);

        $availablePrices = Arr::get($this->queryParameters, $this->pricesQueryParamName, '');
        $availableProducts = Arr::get($this->queryParameters, $this->productsQueryParamName, '');
        $availableQuantities = Arr::get($this->queryParameters, $this->quantitiesQueryParamName, '');

        $newPrices = $this->getNewPrices($availablePrices, $price);
        $newProducts = $this->getNewProducts($availableProducts, $productID);
        $newQuantities = $this->getNewQuantities($availableQuantities, $quantity);

        $this->queryParameters = [
            $this->pricesQueryParamName => $newPrices,
            $this->productsQueryParamName => $newProducts,
            $this->quantitiesQueryParamName => $newQuantities,
        ];

        return $this;
    }

    /**
     * Generates a new product string with the added product ID.
     *
     * @param string $availableProducts The currently available product string.
     * @param string $productID The ID of the new product.
     * @return string The new product string.
     */
    protected function getNewProducts(string $availableProducts, string $productID): string
    {
        $newProducts = $availableProducts . ',' . $productID;

        if ($availableProducts === '')
            $newProducts = $productID;

        return $newProducts;
    }

    /**
     * Generates a new price string with the added price.
     *
     * @param string $availablePrices The currently available price string.
     * @param string $price The price to be added.
     * @return string The new price string.
     */
    protected function getNewPrices(string $availablePrices, string $price): string
    {
        $newPrices = $availablePrices . ',' . $price;

        if ($availablePrices === '')
            $newPrices = $price;

        return $newPrices;
    }

    /**
     * Generates a new quantity string with the added quantity.
     *
     * @param string $availableQuantities The currently available quantity string.
     * @param string $quantity The quantity to be added.
     * @return string The new quantity string.
     */
    protected function getNewQuantities(string $availableQuantities, string $quantity): string
    {
        $newQuantities = $availableQuantities . ',' . $quantity;

        if ($availableQuantities === '')
            $newQuantities = $quantity;

        return $newQuantities;
    }

    /**
     * Sets the locked express method parameter to true.
     *
     * @return BuyLinkInterface
     */
    public function withLockedExpressMethod(): BuyLinkInterface
    {
        $this->setQueryParam($this->lockedExpressQueryParamName, 0);

        return $this;
    }

    /**
     * Sets the locked express method parameter to false.
     *
     * @return BuyLinkInterface
     */
    public function withoutLockedExpressMethod(): BuyLinkInterface
    {
        $this->setQueryParam($this->lockedExpressQueryParamName, 1);

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
        if (! LanguageEnum::isValid($language))
            throw new InvalidArgumentException("'$language' is not a valid language code");

        $this->setQueryParam($this->langQueryParamName, $language);

        return $this;
    }

    /**
     * Builds the buy link with all the necessary parameters.
     *
     * @return CommerceRequestInterface
     */
    public function build(): CommerceRequestInterface
    {
        $this->queryParameters[$this->pHashQueryParamName] = $this->generatePHash();

        foreach($this->queryParameters as $name => $parameter)
            $this->setQueryParam($name, $parameter);

        return $this;
    }

    /**
     * Generates the pHash using HMAC-SHA3-256.
     *
     * @return string The generated pHash.
     */
    protected function generatePHash(): string
    {
        $queryParameters = $this->queryParameters;
        unset($queryParameters[$this->pHashQueryParamName]);

        $asUrlEncoded = http_build_query($queryParameters);
        $lengthOf = strlen($asUrlEncoded);

        $pHashContent = $lengthOf . $asUrlEncoded;
        $hash = hash_hmac('sha3-256', $pHashContent, $this->secretKey);

        return $hash;
    }

    /**
     * Indicates whether the request should redirect.
     *
     * @param bool|null $redirect If provided, sets the redirect option.
     * @return bool The current redirect option value.
     */
    public function isRedirect(bool $redirect = null): bool
    {
        if (! is_null($redirect))
            $this->redirect = $redirect;

        return $this->redirect;
    }
}
