<?php

declare(strict_types=1);

namespace App\Application\Dto\Order;

use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class ClientToken
{
    /**
     * @var string
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="eyJ2ZXJzaW9uIjoyLCJhdXRob3J"
     *         }
     *     },
     * )
     *
     * @Groups({"generate_client_token"})
     *
     * @Assert\Type("string")
     */
    public $clientToken;
}