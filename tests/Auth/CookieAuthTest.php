<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use GuzzleHttp\Cookie\CookieJarInterface;
use JohnPBloch\FluentApi\Auth\CookieAuth;
use JohnPBloch\FluentApi\Tests\WithFakerTrait;
use PHPUnit\Framework\TestCase;

class CookieAuthTest extends TestCase
{
    use WithFakerTrait;

    public function testAddAuthToRequestConfig()
    {
        $domain = $this->faker->safeEmailDomain();
        $name = 'wordpress_auth_cookie';
        $value = md5(random_bytes(20));
        $auth = new CookieAuth($name, $value, $domain);
        $result = $auth->addAuthToRequestConfig([]);
        $this->assertInstanceOf(CookieJarInterface::class, $result['cookies']);
        $cookieData = $result['cookies']->toArray();
        $this->assertEquals($name, $cookieData[0]['Name']);
        $this->assertEquals($value, $cookieData[0]['Value']);
        $this->assertEquals($domain, $cookieData[0]['Domain']);
    }

    public function testCookieAuthOverridesFalseValue()
    {
        $auth = new CookieAuth('foo', 'bar', 'example.com');
        $out = $auth->addAuthToRequestConfig(['cookies' => false]);
        $this->assertInstanceOf(CookieJarInterface::class, $out['cookies']);
    }
}
