<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use JohnPBloch\FluentApi\Auth\NtlmAuth;
use JohnPBloch\FluentApi\Tests\WithFakerTrait;
use PHPUnit\Framework\TestCase;

class NtlmAuthTest extends TestCase
{
    use WithFakerTrait;

    public function testAddAuthToRequestConfig()
    {
        $user = $this->faker->userName();
        $pass = $this->faker->password();
        $ntlm = md5($pass);
        $auth = new NtlmAuth($user, $pass, $ntlm);
        $this->assertEquals([$user, $pass, $ntlm], $auth->addAuthToRequestConfig([])['auth']);
    }
}
