<?php

namespace JohnPBloch\FluentApi\Tests\Fixtures;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class SkipsSendEndpoint extends Endpoint
{
    public bool $skip = false;

    protected function beforeSend(array $requestConfig): array|ResponseInterface
    {
        if (!$this->skip) {
            return $requestConfig;
        }
        return new Response();
    }
}
