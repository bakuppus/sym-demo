<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Command\Binding\Configuration;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class CommandBind
{
    /** @var string */
    public $targetEntity;

    /** @var bool */
    public $isTraverse = false;
}
