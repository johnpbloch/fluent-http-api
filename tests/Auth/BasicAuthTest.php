<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use JohnPBloch\FluentApi\Auth\BasicAuth;
use JohnPBloch\FluentApi\Tests\WithFakerTrait;
use PHPUnit\Framework\TestCase;

class BasicAuthTest extends TestCase
{
    use WithFakerTrait;

    public function testAddAuthToRequestConfig()
    {
        $user = $this->faker->userName();
        $pass = $this->faker->password();
        $auth = new BasicAuth($user, $pass);
        $this->assertEquals(['auth' => [$user, $pass]], $auth->addAuthToRequestConfig([]));
    }
}
