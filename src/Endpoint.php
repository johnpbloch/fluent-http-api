<?php

namespace JohnPBloch\FluentApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use JohnPBloch\FluentApi\Exceptions\ApiNotInitializedException;
use Psr\Http\Message\ResponseInterface;

class Endpoint extends Fluent
{
    protected static Config $config;

    protected string $path = '';

    protected string $method = 'GET';

    public static function setConfig(Config $config): Config
    {
        return self::$config = $config;
    }

    public function send(): ResponseInterface
    {
        $config = self::getConfig();
        $client = $this->getClient();
        $url = Str::finish($config->getBaseUri(), '/') . ltrim($this->getPath(), '/');
        $requestConfig = $this->getRequestConfig();
        $requestConfig = $config->getAuth()->addAuthToRequestConfig($requestConfig);
        $requestConfig = $this->beforeSend($requestConfig);
        if ($requestConfig instanceof ResponseInterface) {
            return $requestConfig;
        }
        $response = $client->request($this->method, $url, $requestConfig);
        return $this->afterSend($response);
    }

    protected function getConfig(): Config
    {
        if (empty(self::$config)) {
            throw new ApiNotInitializedException('Attempting to access configuration without initializing first!');
        }
        return self::$config;
    }

    public function path(string $path): static
    {
        $this->path = $path;
        return $this;
    }

    protected function getPath(): string
    {
        return $this->path;
    }

    public function method(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    protected function getClient(): ClientInterface
    {
        return new Client();
    }

    protected function getRequestConfig(): array
    {
        $config = $this->initialConfig();
        foreach (get_class_methods($this) as $method) {
            if (str_starts_with($method, 'setRequestConfig') && strlen($method) >= 20) {
                $key = Str::snake(substr($method, 16));
                $value = $this->{$method}($config[$key] ?? null);
                $config[$key] = $value;
            } elseif (str_starts_with($method, 'mergeRequestConfig') && strlen($method) >= 22) {
                $key = Str::snake(substr($method, 18));
                $config[$key] = Arr::wrap($config[$key] ?? []);
                $config[$key] = array_merge($config[$key], $this->{$method}());
            }
        }
        return $config;
    }

    protected function initialConfig(): array
    {
        return match (strtoupper($this->method)) {
            'GET' => ['query' => $this->getAttributes()],
            'POST', 'PUT' => ['form_params' => $this->getAttributes()],
            default => [],
        };
    }

    protected function beforeSend(array $requestConfig): array|ResponseInterface
    {
        return $requestConfig;
    }

    protected function afterSend(ResponseInterface $response): ResponseInterface
    {
        return $response;
    }

    public static function make(array $attributes = []): static
    {
        return new static($attributes);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        $endpoint = new static;
        $endpoint->{$name}(...$arguments);
        return $endpoint;
    }
}
