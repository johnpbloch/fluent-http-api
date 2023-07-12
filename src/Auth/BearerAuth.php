<?php

namespace JohnPBloch\FluentApi\Auth;

class BearerAuth extends HeaderAuth
{
    public function __construct(string $token)
    {
        parent::__construct('Authorization', "Bearer $token");
    }
}
