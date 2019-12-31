<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use Exception;

abstract class Fixture implements FixtureInterface
{
    protected $fixtureFile;

    /**
     * {@inheritDoc}
     */
    public function getFile(): string
    {
        if ('' === $this->fixtureFile) {
            throw new Exception('Fixture file variable `$fixtureFile` was not defined');
        }

        return $this->fixtureFile;
    }
}
