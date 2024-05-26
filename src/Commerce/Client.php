<?php

namespace TwoCheckout\Commerce;

use TwoCheckout\Commerce\Interfaces\Requests\CommerceRequestInterface;
use TwoCheckout\Exceptions\HTTP\CurlException;
use TwoCheckout\Interfaces\ClientInterface;
use TwoCheckout\Interfaces\HTTP\CurlResponseHandlerInterface;
use TwoCheckout\REST6\Enums\EnvironmentEnum;
use TwoCheckout\Traits\HasHTTPHeaderTrait;

class Client implements ClientInterface
{
    use HasHTTPHeaderTrait;

    protected CommerceRequestInterface $request;
    protected CurlResponseHandlerInterface $responseHandler;

    public function __construct(
        CommerceRequestInterface $request,
        CurlResponseHandlerInterface $responseHandler
    )
    {
        $this->request = $request->build();
        $this->responseHandler = $responseHandler;
    }

    /**
     * @throws CurlException
     */
    public function execute(string $environment): object
    {
        if (! EnvironmentEnum::isValid($environment))
            throw new \InvalidArgumentException("'$environment' is not a valid environment.");

        $handle = curl_init();

        $fullUrl = $this->getFullUrl($environment);
        $method = $this->request->getMethod();
        $headers = $this->request->getHeaders();
        $body = $this->request->getBody();
        $encodedBody = $this->request->getEncoder()->encode($body);

        if ($this->request->isRedirect())
        {
            header("Location: $fullUrl");
            exit();
        }

        curl_setopt_array($handle, [
            CURLOPT_URL => $fullUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $encodedBody,
        ]);

        $response = curl_exec($handle);

        if ($response === false)
            throw new CurlException("An error occurred while trying to send the request: " . curl_error($handle));

        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $headers = $this->parseHeaders(substr($response, 0, $headerSize));
        $body = $this->request->getDecoder()->decode(substr($response, $headerSize));

        $statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        return $this->responseHandler->handle(
            $statusCode,
            $headers,
            $body
        );
    }

    protected function getFullUrl(string $environment): string
    {
        $url = $environment;
        $uri = $this->request->getUri();
        $queryParameters = $this->request->getQueryParams();

        return $url . $uri . "?$queryParameters";
    }
}