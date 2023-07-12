<?php

namespace JohnPBloch\FluentApi\Auth;

class DigestAuth extends BasicAuth
{
    public function __construct(string $user, string $password, private string $digest)
    {
        parent::__construct($user, $password);
    }

    public function addAuthToRequestConfig(array $config): array
    {
        $config = parent::addAuthToRequestConfig($config);
        $config['auth'][] = $this->digest;
        return $config;
    }
}
