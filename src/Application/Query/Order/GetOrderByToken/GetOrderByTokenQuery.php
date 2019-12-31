<?php

declare(strict_types=1);

namespace App\Application\Query\Order\GetOrderByToken;

class GetOrderByTokenQuery
{
    /** @var string */
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}