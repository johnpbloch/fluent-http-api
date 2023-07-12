<?php

namespace JohnPBloch\FluentApi\Auth;

class NullAuth implements AuthInterface
{
    public function addAuthToRequestConfig(array $config): array
    {
        return $config;
    }
}
