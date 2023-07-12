<?php

namespace JohnPBloch\FluentApi\Auth;

interface AuthInterface
{
    public function addAuthToRequestConfig(array $config): array;
}
