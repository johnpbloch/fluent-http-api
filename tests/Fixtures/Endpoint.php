<?php

namespace JohnPBloch\FluentApi\Tests\Fixtures;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use JohnPBloch\FluentApi\Endpoint as BaseEndpoint;
use Psr\Http\Message\ResponseInterface;

abstract class Endpoint extends BaseEndpoint
{
    public MockHandler $mockHandler;

    protected function getPath(): string
    {
        return $this->get('path', '');
    }

    protected function getClient(): ClientInterface
    {
        return new Client(['handler' => HandlerStack::create($this->getMockHandler())]);
    }

    public function setUpResponse(?ResponseInterface $response = null): Endpoint
    {
        $response ??= new Response();
        $this->getMockHandler()->append($response);
        return $this;
    }

    public function getMockHandler(): MockHandler
    {
        $this->mockHandler ??= new MockHandler();
        return $this->mockHandler;
    }
}
