<?php

namespace JohnPBloch\FluentApi\Tests\Fixtures;

class GetEndpointWithQuery extends Endpoint
{
    protected function mergeRequestConfigQuery(): array
    {
        $attributes = $this->getAttributes();
        return array_diff_key($attributes, ['method' => '', 'path' => '']);
    }
}
