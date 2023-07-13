<?php

namespace JohnPBloch\FluentApi\Tests;

use GuzzleHttp\Psr7\Response;
use JohnPBloch\FluentApi\Auth\NullAuth;
use JohnPBloch\FluentApi\Config;
use JohnPBloch\FluentApi\Tests\Fixtures\Endpoint;
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
        $request = Endpoint::make();
        $request->setUpResponse()
            ->method('GET')
            ->path('get/query')
            ->city($city)
            ->state($state)
            ->send();
        $lastRequest = $request->mockHandler->getLastRequest();
        $query = http_build_query(compact('city', 'state'), encoding_type: PHP_QUERY_RFC3986);
        $this->assertEquals("https://api.example.com/v2/get/query?$query", (string)$lastRequest->getUri());
        $this->assertEquals('GET', $lastRequest->getMethod());
    }

    public function testStandardPostRequest()
    {
        $title = implode(' ',$this->faker->words(6));
        $content = '<p>' . implode('</p><p>', $this->faker->paragraphs(4)) . '</p>';
        $request = Endpoint::make();
        $request->setUpResponse()
            ->method('POST')
            ->path('article')
            ->title($title)
            ->content($content)
            ->send();
        $lastRequest = $request->mockHandler->getLastRequest();
        $query = http_build_query(compact('title', 'content'));
        $this->assertEquals('https://api.example.com/v2/article', (string)$lastRequest->getUri());
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals($query, $lastRequest->getBody()->getContents());
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
