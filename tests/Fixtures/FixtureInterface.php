<?php

namespace App\Tests\Fixtures;

use Exception;

interface FixtureInterface
{
    /**
     * @throws Exception
     */
    public function getFile(): string;
}
