<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use JohnPBloch\FluentApi\Auth\BearerAuth;
use PHPUnit\Framework\TestCase;

class BearerAuthTest extends TestCase
{
    public function testBearerAuth()
    {
        $token = base64_encode(random_bytes(20));
        $auth = new BearerAuth($token);
        $this->assertEquals(['headers' => ['Authorization' => "Bearer $token"]], $auth->addAuthToRequestConfig([]));
    }
}
