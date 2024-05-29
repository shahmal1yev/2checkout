<?php

namespace TwoCheckout\ConvertPlus\BuyLink;

use TwoCheckout\Commerce\Interfaces\ConvertPlus\BuyLink\BuyLinkBuilderInterface;
use TwoCheckout\ConvertPlus\Enums\EnvironmentEnum;
use TwoCheckout\Exceptions\Data\InvalidEnvironmentException;
use TwoCheckout\Exceptions\HTTP\CurlException;
use TwoCheckout\Interfaces\ClientInterface;
use TwoCheckout\Interfaces\HTTP\CurlResponseHandlerInterface;
use TwoCheckout\Traits\HasHTTPHeaderTrait;

class BuyLinkGenerator implements ClientInterface
{
    use HasHTTPHeaderTrait;

    protected BuyLinkBuilderInterface $buyLinkBuilder;
    protected CurlResponseHandlerInterface $responseHandler;

    public function __construct(
        BuyLinkBuilderInterface $buyLinkBuilder,
        CurlResponseHandlerInterface $curlResponseHandler
    )
    {
        $this->buyLinkBuilder = $buyLinkBuilder->build();
        $this->responseHandler = $curlResponseHandler;
    }

    /**
     * @throws CurlException
     * @throws InvalidEnvironmentException
     */
    public function execute(string $environment): object
    {
        if (! EnvironmentEnum::isValid($environment))
            throw new InvalidEnvironmentException("'$environment' is not a valid environment.");

        $response = $this->generate($environment);

        $this->modifyResponse($response);

        return $response;
    }

    protected function modifyResponse(&$response)
    {
        $link = $this->getLinkBySignature($response);

        $response->body->link = $link;
    }

    protected function getLinkBySignature(object $response): string
    {
        $this->buyLinkBuilder->setQueryParam('signature', $response->body->signature);

        $url = 'https://secure.2checkout.com/checkout/buy?';
        $queryParameters = http_build_query($this->buyLinkBuilder->getQueryParams(false), PHP_QUERY_RFC3986);

        $fullUrl = $url . $queryParameters;

        return $fullUrl;
    }

    /**
     * @throws CurlException
     */
    protected function generate(string $environment): object
    {
        $handle = curl_init();

        $url = $this->prepareURL($environment);
        $body = $this->buyLinkBuilder->getBody();
        $method = $this->buyLinkBuilder->getMethod();
        $headers = $this->buyLinkBuilder->getHeaders();
        $encodedBody = $this->buyLinkBuilder->getEncoder()->encode($body);

        curl_setopt_array($handle, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $encodedBody
        ]);

        $response = curl_exec($handle);

        if ($response === false)
            throw new CurlException("An error occurred while trying to send the request: " . curl_error($handle));

        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $headers = $this->parseHeaders(substr($response, 0, $headerSize));
        $body = $this->buyLinkBuilder->getDecoder()->decode(substr($response, $headerSize));

        $statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        return $this->responseHandler->handle(
            $statusCode,
            $headers,
            $body
        );
    }

    protected function prepareURL(string $environment): string
    {
        $url = $environment . $this->buyLinkBuilder->getUri();

        return $url;
    }
}