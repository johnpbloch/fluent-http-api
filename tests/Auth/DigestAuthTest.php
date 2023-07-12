<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use JohnPBloch\FluentApi\Auth\DigestAuth;
use JohnPBloch\FluentApi\Tests\WithFakerTrait;
use PHPUnit\Framework\TestCase;

class DigestAuthTest extends TestCase
{
    use WithFakerTrait;

    public function testAddAuthToRequestConfig()
    {
        $user = $this->faker->userName();
        $pass = $this->faker->password();
        $digest = md5(random_bytes(20));
        $auth = new DigestAuth($user, $pass, $digest);
        $this->assertEquals([$user, $pass, $digest], $auth->addAuthToRequestConfig([])['auth']);
    }
}
