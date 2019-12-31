<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class FailedJob
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $connection;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $queue;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $payload;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $failed_at;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $exception;
}