<?php

declare(strict_types=1);

namespace App\Application\Command\Order\GenerateClientToken;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Domain\Order\Core\OrderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Application\Dto\Order\ClientToken;

/**
 * @ApiResource(
 *   shortName="Payment",
 *   messenger=true,
 *   collectionOperations={},
 *   itemOperations={
 *          "generate_client_token"={
 *              "method"="PUT",
 *              "path"="/orders/{id}/client-token",
 *              "output"=ClientToken::class,
 *              "normalization_context"={"groups"={"Default", "generate_client_token"}},
 *              "denormalization_context"={"groups"={"Default", "generate_client_token"}},
 *              "swagger_context"={
 *                  "summary"="Generate braintree client token by order token",
 *              }
 *          }
 *    }
 * )
 */
final class GenerateClientTokenCommand
{
    /**
     * @var string
     *
     * @ApiProperty(identifier=true)
     *
     * @Assert\Type("string")
     */
    public $token;

    /**
     * @var OrderInterface
     *
     * @Assert\Type("object")
     */
    public $order;
}