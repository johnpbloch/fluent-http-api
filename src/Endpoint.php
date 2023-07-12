<?php

namespace JohnPBloch\FluentApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use JohnPBloch\FluentApi\Exceptions\ApiNotInitializedException;
use Psr\Http\Message\ResponseInterface;

abstract class Endpoint extends Fluent
{
    protected static Config $config;

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
        $response = $client->request($this->method ?? 'GET', $url, $requestConfig);
        return $this->afterSend($response);
    }

    protected function getConfig(): Config
    {
        if (empty(self::$config)) {
            throw new ApiNotInitializedException('Attempting to access configuration without initializing first!');
        }
        return self::$config;
    }

    abstract protected function getPath(): string;

    protected function getClient(): ClientInterface
    {
        return new Client();
    }

    protected function getRequestConfig(): array
    {
        $config = [];
        foreach (get_class_methods($this) as $method) {
            if (preg_match('/^set(.+)RequestConfig$/', $method, $match)) {
                $key = Str::snake($match[1]);
                $config[$key] = $this->{$method}($config[$key] ?? null);
            }
        }
        return $config;
    }

    protected function beforeSend(array $requestConfig): array
    {
        return $requestConfig;
    }

    protected function afterSend(ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
