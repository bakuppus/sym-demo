<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use Exception;
use ReflectionClass;
use ReflectionException;

final class FixturePathResolver implements FixturePathResolverInterface
{
    /** @var FixtureInterface */
    private $fixture;

    public function __construct(FixtureInterface $fixture)
    {
        $this->fixture = $fixture;
    }

    /**
     * @return string
     * @throws ReflectionException
     * @throws Exception
     */
    public function resolve(): string
    {
        $fixtureName = get_class($this->fixture);
        $path = $this->generatePath($fixtureName);

        return sprintf('%s/%s', $path, $this->fixture->getFile());
    }

    /**
     * @param string $resolverName
     *
     * @return string
     * @throws ReflectionException
     */
    protected function generatePath(string $resolverName): string
    {
        $filename = (new ReflectionClass($resolverName))->getFileName();
        $peaces = explode('/', $filename);
        array_pop($peaces);

        return implode('/', $peaces);
    }
}
