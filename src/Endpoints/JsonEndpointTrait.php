<?php

namespace JohnPBloch\FluentApi\Endpoints;

use Psr\Http\Message\StreamInterface;

trait JsonEndpointTrait
{
    protected function mergeRequestConfigHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function setRequestConfigBody(): mixed
    {
        $body = $this->body ?? null;
        return match (true) {
            $body === null, $body instanceof StreamInterface, is_resource($body), is_string($body) => $body,
            default => json_encode($body),
        };
    }

    protected function setRequestConfigFormParams():void
    {
    }
}
