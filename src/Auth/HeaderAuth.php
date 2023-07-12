<?php

namespace JohnPBloch\FluentApi\Auth;

class HeaderAuth implements AuthInterface
{
    public function __construct(private string $name, private string $value)
    {
    }

    public function addAuthToRequestConfig(array $config): array
    {
        $config['headers'] ??= [];
        $config['headers'][$this->name] = $this->value;
        return $config;
    }
}
