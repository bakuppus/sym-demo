<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Fidry\AliceDataFixtures\LoaderInterface;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

final class FixtureContext implements Context, KernelAwareContext
{
    /** @var LoaderInterface */
    private $loader;

    /** @var KernelInterface */
    private $kernel;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given the fixtures :fixturesFile are loaded
     * @Given the fixtures file :fixturesFile is loaded
     *
     * @param string $fixturesFile Path to the fixtures
     */
    public function thereAreFixtures($fixturesFile): void
    {
        $this->loader->load([$fixturesFile]);
    }

    /**
     * @Given the following fixtures are loaded:
     * @Given the following fixtures files are loaded:
     * @Given /^the following fixtures are loaded using the (append|delete|truncate) purger:$/
     *
     * @param TableNode $fixtures
     * @param null $purgeMode
     */
    public function thereAreSeveralFixtures(TableNode $fixtures, $purgeMode = null): void
    {
        switch ((string)$purgeMode) {
            case 'append';
                $purgeMode = PurgeMode::createNoPurgeMode();
                break;
            case 'truncate';
                $purgeMode = PurgeMode::createTruncateMode();
                break;
            case 'delete';
            case '';
                $purgeMode = PurgeMode::createDeleteMode();
                break;
            default;
                throw new RuntimeException("Invalid purge mode");
                break;
        }

        $fixturesFiles = [];

        foreach ($fixtures->getRows() as $fixturesFileRow) {
            $fixturesFiles[] = $fixturesFileRow[0];
        }

        $this->loader->load($fixturesFiles, $parameters = [], $objects = [], $purgeMode);
    }

    /**
     * @Then I empty the database
     */
    public function iEmptyTheDatabase(): void
    {
        $this->loader->load([], $parameters = [], $objects = [], PurgeMode::createTruncateMode());
    }

    /**
     * @Then I populate the :index index
     *
     * @param string $index
     *
     * @throws Exception
     */
    public function iPopulateTheIndex(string $index): void
    {
        $application = new Application($this->kernel);
        $command = $application->find('fos:elastica:populate');
        $input = new ArrayInput([
            'command' => 'fos:elastica:populate',
            '--index' => $index,
        ]);
        $output = new BufferedOutput();
        $command->run($input, $output);
    }

    /**
     * @Then I reset the :index index
     *
     * @param string $index
     *
     * @throws Exception
     */
    public function iResetTheIndex(string $index): void
    {
        $application = new Application($this->kernel);
        $command = $application->find('fos:elastica:reset');
        $input = new ArrayInput([
            'command' => 'fos:elastica:reset',
            '--index' => $index,
        ]);
        $output = new BufferedOutput();
        $command->run($input, $output);
    }

    /**
     * @Then I populate the indexes
     *
     * @throws Exception
     */
    public function iPopulateTheIndexes(): void
    {
        $application = new Application($this->kernel);
        $command = $application->find('fos:elastica:populate');
        $input = new ArrayInput(['command' => 'fos:elastica:populate']);
        $output = new BufferedOutput();
        $command->run($input, $output);
    }

    /**
     * @Then I reset the indexes
     *
     * @throws Exception
     */
    public function iResetTheIndexes(): void
    {
        $application = new Application($this->kernel);
        $command = $application->find('fos:elastica:reset');
        $input = new ArrayInput([
            'command' => 'fos:elastica:reset',
        ]);
        $output = new BufferedOutput();
        $command->run($input, $output);
    }
}
