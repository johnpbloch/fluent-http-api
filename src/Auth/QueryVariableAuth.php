<?php

namespace JohnPBloch\FluentApi\Auth;

class QueryVariableAuth implements AuthInterface
{
    public function __construct(private string $name, private string $value)
    {
    }

    public function addAuthToRequestConfig(array $config): array
    {
        $config['query'] ??= [];
        $config['query'][$this->name] = $this->value;
        return $config;
    }
}
