<?php

namespace JohnPBloch\FluentApi\Tests\Auth;

use JohnPBloch\FluentApi\Auth\QueryVariableAuth;
use JohnPBloch\FluentApi\Tests\WithFakerTrait;
use PHPUnit\Framework\TestCase;

class QueryVariableAuthTest extends TestCase
{
    use WithFakerTrait;

    public function testAddAuthToRequestConfig()
    {
        $key = $this->faker->randomElement(['token', 'key', 'api_key']);
        $value = md5(random_bytes(20));
        $auth = new QueryVariableAuth($key, $value);
        $in = [
            'query' => [
                'Test' => random_int(1, 99),
            ],
        ];
        $out = $auth->addAuthToRequestConfig($in);
        $this->assertEquals($value, $out['query'][$key]);
        $this->assertEquals($in['query']['Test'], $out['query']['Test']);
    }
}
