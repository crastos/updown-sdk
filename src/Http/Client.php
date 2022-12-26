<?php

namespace Crastos\Updown\Http;

use Curl\Curl as CurlHttpClient;
use GuzzleHttp\ClientInterface as GuzzleHttpClient;
use Illuminate\Http\Client\PendingRequest as LaravelHttpClient;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyHttpClient;

/**
 * Http client for updown.io API
 *
 * @method post(string $uri, array $options = []): array|int|string|float|bool
 * @method get(string $uri, array $options = []): array|int|string|float|bool
 * @method patch(string $uri, array $options = []): array|int|string|float|bool
 * @method put(string $uri, array $options = []): array|int|string|float|bool
 * @method delete(string $uri, array $options = []): array|int|string|float|bool
 * @method options(string $uri, array $options = []): array|int|string|float|bool
 * @method head(string $uri, array $options = []): array|int|string|float|bool
 * @method trace(string $uri, array $options = []): array|int|string|float|bool
 *
 * @phpstan-consistent-constructor
 */
class Client
{
    /**
     * updown.io Client.
     */
    public function __construct(
        protected ?string $secret = null,
        protected ?string $base_uri = 'https://updown.io/api/',
        protected CurlHttpClient|LaravelHttpClient|SymfonyHttpClient|GuzzleHttpClient|null $client = null,
    ) {
    }

    /**
     * Set the secret.
     *
     * @param  string  $secret
     * @return static
     */
    public function withSecret(string $secret): static
    {
        assert(is_null($this->client), 'Cannot set secret after client has been set.');

        return new static($secret, $this->base_uri);
    }

    /**
     * Set the base uri.
     *
     * @param  string  $base_uri
     * @return static
     */
    public function withBaseUri(string $base_uri): static
    {
        assert(is_null($this->client), 'Cannot set base_uri after client has been set.');

        return new static($this->secret, $base_uri);
    }

    /**
     * Set the HTTP client.
     *
     * Secret and base_uri will be ignored if a client is set.
     *
     * @param  LaravelHttpClient|SymfonyHttpClient|GuzzleHttpClient  $client
     * @return static
     */
    public function withClient(LaravelHttpClient|SymfonyHttpClient|GuzzleHttpClient $client): static
    {
        return new static(null, null, $client);
    }

    /**
     * Make a request to updown.io API.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $options
     * @return array|int|string|float|bool
     */
    public function request(string $method, string $uri, iterable $options = []): array|int|string|float|bool
    {
        return match (true) {
            $this->is(LaravelHttpClient::class) => $this->httpClient()->{strtolower($method)}($uri, (array) $options)->json(),
            $this->is(SymfonyHttpClient::class) => json_decode($this->httpClient()->request($method, $uri, (array) $options)->getContent(), true),
            $this->is(GuzzleHttpClient::class) => json_decode($this->httpClient()->request($method, $uri, (array) $options)->getBody(), true),
            $this->is(CurlHttpClient::class) => json_decode($this->httpClient()->{strtolower($method)}($uri, (array) $options), true),
            default => throw new InvalidArgumentException('Invalid HTTP client.'),
        };
    }

    /**
     * Get the HTTP client.
     *
     * @throws InvalidArgumentException
     */
    protected function httpClient(): LaravelHttpClient|SymfonyHttpClient|GuzzleHttpClient|CurlHttpClient
    {
        return $this->client ??= match (true) {
            class_exists(LaravelHttpClient::class) => $this->laravelHttpClient(),
            class_exists(\Symfony\Component\HttpClient\HttpClient::class) => $this->symfonyHttpClient(),
            class_exists(\GuzzleHttp\Client::class) => $this->guzzleHttpClient(),
            class_exists(CurlHttpClient::class) => $this->curlHttpClient(),
            default => throw new InvalidArgumentException('No HTTP client found. Try running `composer suggests crastos/updown-sdk` from your terminal.'),
        };
    }

    /**
     * Get the Laravel HTTP client.
     *
     * @return LaravelHttpClient
     */
    protected function laravelHttpClient(): LaravelHttpClient
    {
        if (\Illuminate\Support\Facades\Http::getFacadeRoot()) {
            return \Illuminate\Support\Facades\Http::baseUrl($this->base_uri)->withHeaders([
                'x-api-key' => $this->secret,
            ])->retry(3, 100);
        }

        return (new \Illuminate\Http\Client\Factory)->baseUrl($this->base_uri)->withHeaders([
            'x-api-key' => $this->secret,
        ])->retry(3, 100);
    }

    /**
     * Get the Symfony HTTP client.
     *
     * @return SymfonyHttpClient
     *
     * @throws InvalidArgumentException
     */
    protected function symfonyHttpClient(): SymfonyHttpClient
    {
        return \Symfony\Component\HttpClient\HttpClient::createForBaseUri($this->base_uri, [
            'headers' => [
                'x-api-key' => $this->secret,
            ],
            'max_retries' => 3,
            'retry_delay' => 100,
        ]);
    }

    /**
     * Get the Guzzle HTTP client.
     *
     * @return GuzzleHttpClient
     */
    protected function guzzleHttpClient(): GuzzleHttpClient
    {
        return new \GuzzleHttp\Client([
            'base_uri' => $this->base_uri,
            'headers' => [
                'x-api-key' => $this->secret,
            ],
            'retries' => 3,
            'delay' => 100,
        ]);
    }

    /**
     * Get the Curl HTTP client.
     *
     * @return CurlHttpClient
     */
    protected function curlHttpClient(): CurlHttpClient
    {
        $client = new CurlHttpClient($this->base_uri);
        $client->setHeader('x-api-key', $this->secret);
        $client->setRetry(3);

        return $client;
    }

    protected function is($class_or_interface)
    {
        return $this->httpClient() instanceof $class_or_interface;
    }

    public function __call($name, $arguments)
    {
        return $this->request(strtoupper($name), ...$arguments);
    }
}
