<?php

namespace JohnPBloch\FluentApi\Tests;

use GuzzleHttp\Psr7\Response;
use JohnPBloch\FluentApi\Auth\NullAuth;
use JohnPBloch\FluentApi\Config;
use JohnPBloch\FluentApi\Tests\Fixtures\GetEndpointWithQuery;
use JohnPBloch\FluentApi\Tests\Fixtures\PostJsonEndpoint;
use JohnPBloch\FluentApi\Tests\Fixtures\SkipsSendEndpoint;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    use WithFakerTrait;

    /**
     * @beforeClass
     */
    public static function setUpConfig()
    {
        Config::initialize('https://api.example.com/v2', new NullAuth());
    }

    public function testGetRequestWithQuery()
    {
        $city = $this->faker->city();
        $state = $this->faker->stateAbbr();
        /** @var GetEndpointWithQuery $request */
        $request = GetEndpointWithQuery::make()
            ->method('GET')
            ->path('get/query');
        $request->city($city)
            ->state($state)
            ->setUpResponse()
            ->send();
        $lastRequest = $request->mockHandler->getLastRequest();
        $query = http_build_query(compact('city', 'state'), encoding_type: PHP_QUERY_RFC3986);
        $this->assertEquals("https://api.example.com/v2/get/query?$query", (string)$lastRequest->getUri());
        $this->assertEquals('GET', $lastRequest->getMethod());
    }

    public function testPostJsonRequest()
    {
        /** @var PostJsonEndpoint $request */
        $request = PostJsonEndpoint::make()->method('post')->path('send/json');
        $data = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
        ];
        $request->setUpResponse()->body($data)->send();
        $lastRequest = $request->mockHandler->getLastRequest();
        $this->assertEquals('application/json', $lastRequest->getHeaderLine('Content-Type'));
        $this->assertEquals('application/json', $lastRequest->getHeaderLine('Accept'));
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertJsonStringEqualsJsonString(json_encode($data), $lastRequest->getBody());
    }

    public function testBeforeSendSkipsSendWhenReturningResponse()
    {
        $request = new SkipsSendEndpoint;
        $res1 = new Response();
        $res2 = new Response();
        $request->getMockHandler()->append($res1, $res2);
        $request->skip = false;
        $this->assertSame($res1, $request->send());
        $request->skip = true;
        $this->assertNotSame($res2, $request->send());
    }
}
