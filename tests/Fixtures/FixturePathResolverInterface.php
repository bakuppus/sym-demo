<?php

namespace App\Tests\Fixtures;

interface FixturePathResolverInterface
{
    public function resolve(): string;
}
