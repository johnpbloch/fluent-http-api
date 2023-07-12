<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use JohnPBloch\FluentApi\Auth\HeaderAuth;
use JohnPBloch\FluentApi\Tests\WithFakerTrait;
use PHPUnit\Framework\TestCase;

class HeaderAuthTest extends TestCase
{
    use WithFakerTrait;

    public function testAddAuthToRequestConfig()
    {
        $name = $this->faker->randomElement(['X-Api-Key', 'X-Auth-Token']);
        $value = base64_encode(random_bytes(20));
        $auth = new HeaderAuth($name, $value);
        $this->assertEquals(['headers' => [$name => $value]], $auth->addAuthToRequestConfig([]));
    }

    public function testHeaderAuthKeepsOtherHeaders()
    {
        $token = base64_encode(random_bytes(20));
        $auth = new HeaderAuth('X-Api-Key', $token);
        $mime = $this->faker->mimeType();
        $authenticated = $auth->addAuthToRequestConfig(['headers' => ['Content-Type' => $mime]]);
        $this->assertEquals($mime, $authenticated['headers']['Content-Type']);
    }
}
