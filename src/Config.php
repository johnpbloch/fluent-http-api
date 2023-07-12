<?php

namespace JohnPBloch\FluentApi;

use JohnPBloch\FluentApi\Auth\AuthInterface;

class Config
{
    protected string $baseUri = '';
    protected AuthInterface $auth;

    public static function initialize(string $baseUri, AuthInterface $auth): static
    {
        $config = new static;
        $config->baseUri = $baseUri;
        $config->auth = $auth;
        return $config->setConfigOnBaseEndpoint();
    }

    /**
     * @return AuthInterface
     */
    public function getAuth(): AuthInterface
    {
        return $this->auth;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    protected function setConfigOnBaseEndpoint(): static
    {
        return Endpoint::setConfig($this);
    }
}
