<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behatch\Context\BaseContext;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

class DoctrineContext extends BaseContext implements Context
{
    /** @var ManagerRegistry */
    private $doctrine;

    /**
     * DoctrineContext constructor.
     *
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Then entity :className has one item with condition:
     */
    public function entityHasOneItemWithCondition(string $className, TableNode $condition)
    {
        $item = $this->getEntityHasOneItemWithCondition($className, $condition);
        Assert::notNull($item, 'Entity doesn\'t exist');
    }

    /**
     * @Then entity :className doesnt have item with condition:
     */
    public function entityDoesntHaveItemWithCondition(string $className, TableNode $condition)
    {
        $item = $this->getEntityHasOneItemWithCondition($className, $condition);
        Assert::null($item, 'Entity doesn\'t exist');
    }

    private function getEntityHasOneItemWithCondition(string $className, TableNode $condition): ?object
    {
        /** @var EntityManagerInterface $manager */
        $manager = $this->doctrine->getManagerForClass($className);

        $qb = $manager->createQueryBuilder()
            ->from($className, 't')
            ->select('t');
        $it = $condition->getIterator();
        foreach ($it as $key => $fields) {
            foreach ($fields as $fieldName => $fieldValue) {
                $valueParam = $fieldName . $key;
                $qb->andWhere(sprintf('t.%s = :%s', $fieldName, $valueParam));
                $qb->setParameter($valueParam, $fieldValue);
            }
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
