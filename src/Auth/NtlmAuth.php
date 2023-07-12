<?php

namespace JohnPBloch\FluentApi\Auth;

class NtlmAuth extends BasicAuth
{
    public function __construct(string $user, string $password, private string $ntlm)
    {
        parent::__construct($user, $password);
    }

    public function addAuthToRequestConfig(array $config): array
    {
        $config = parent::addAuthToRequestConfig($config);
        $config['auth'][] = $this->ntlm;
        return $config;
    }
}
