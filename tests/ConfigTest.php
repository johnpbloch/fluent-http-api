<?php

namespace JohnPBloch\FluentApi\Tests;

use JohnPBloch\FluentApi\Auth\NullAuth;
use JohnPBloch\FluentApi\Config;
use JohnPBloch\FluentApi\Endpoint;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testInitialize()
    {
        $baseUri = 'http://api.example.com/v1/';
        $auth = new NullAuth();
        $config = Config::initialize($baseUri, $auth);
        $this->assertEquals($baseUri, $config->getBaseUri());
        $this->assertSame($auth, $config->getAuth());
        $reflection = new \ReflectionProperty(Endpoint::class, 'config');
        $reflection->setAccessible(true);
        $this->assertSame($config, $reflection->getValue());
    }
}
