<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use JohnPBloch\FluentApi\Auth\BearerAuth;
use JohnPBloch\FluentApi\Tests\WithFakerTrait;
use PHPUnit\Framework\TestCase;

class BearerAuthTest extends TestCase
{
    use WithFakerTrait;

    public function testBearerAuth()
    {
        $token = base64_encode(random_bytes(20));
        $auth = new BearerAuth($token);
        $this->assertEquals(['headers' => ['Authorization' => "Bearer $token"]], $auth->addAuthToRequestConfig([]));
    }

    public function testBearerAuthKeepsOtherHeaders()
    {
        $token = base64_encode(random_bytes(20));
        $auth = new BearerAuth($token);
        $mime = $this->faker->mimeType();
        $authenticated = $auth->addAuthToRequestConfig(['headers' => ['Content-Type' => $mime]]);
        $this->assertEquals($mime, $authenticated['headers']['Content-Type']);
    }
}
