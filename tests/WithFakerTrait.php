<?php

namespace JohnPBloch\FluentApi\Tests;

use Faker\Factory;
use Faker\Generator;

trait WithFakerTrait
{
    protected Generator $faker;

    /**
     * @before
     */
    protected function createFakerObject(): void
    {
        $this->faker ??= Factory::create();
    }
}
