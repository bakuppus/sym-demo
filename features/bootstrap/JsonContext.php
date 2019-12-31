<?php

declare(strict_types=1);

use Behatch\Context\JsonContext as BaseJsonContext;
use Behatch\HttpCall\HttpCallResultPool;

final class JsonContext extends BaseJsonContext
{
    public function __construct(HttpCallResultPool $httpCallResultPool)
    {
        parent::__construct($httpCallResultPool);
    }

    /**
     * Checks, that given JSON node is equal to the given number
     *
     * @Then the JSON node :node should be greater than the number :number
     */
    public function theJsonNodeShouldBeGreaterThanTheNumber($node, $number)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if ($actual <= (float)$number && $actual <= (int)$number) {
            throw new Exception(
                sprintf('The node value is `%s`', json_encode($actual))
            );
        }
    }
}
