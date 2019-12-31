<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use ReflectionException;

trait FixtureLoaderTrait
{
    /** @var string */
    protected $fixture;

    /**
     * @param FixtureInterface $fixture
     *
     * @return string
     * @throws ReflectionException
     */
    protected function loadFixture(FixtureInterface $fixture): string
    {
        $fixturePathResolver = new FixturePathResolver($fixture);
        $path = $fixturePathResolver->resolve();

        return file_get_contents($path);
    }
}
