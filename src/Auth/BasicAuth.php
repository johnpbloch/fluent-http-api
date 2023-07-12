<?php

namespace JohnPBloch\FluentApi\Auth;

class BasicAuth implements AuthInterface
{
    public function __construct(private string $user, private string $password)
    {
    }

    public function addAuthToRequestConfig(array $config): array
    {
        $config['auth'] = [$this->user, $this->password];
        return $config;
    }
}
