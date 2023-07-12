<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use JohnPBloch\FluentApi\Auth\NullAuth;
use PHPUnit\Framework\TestCase;

class NullAuthTest extends TestCase
{
    public function testAddAuthToRequestConfig()
    {
        $in = ['Test' => random_int(1, 1000)];
        $auth = new NullAuth();
        $this->assertSame($in, $auth->addAuthToRequestConfig($in));
    }
}
