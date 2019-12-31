<?php

declare(strict_types=1);

namespace App\Domain\Order;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Promotion\Membership;
use Doctrine\ORM\Mapping as ORM;
use App\Application\Command\Order\PaymentLink\SendPaymentLinkCommand;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 *
 * @ApiResource(
 *     messenger="input",
 *     collectionOperations={},
 *     itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/orders/memberships/{id}",
 *              "normalization_context"={
 *                  "groups"={
 *                      "Default",
 *                      "get_order",
 *                      "get_order_membership",
 *                      "get_membership",
 *                      "list_memberships",
 *                      "get_fee",
 *                      "get_club",
 *                      "get_membership_card"
 *                  }
 *              },
 *              "swagger_context"={
 *                  "summary"="Find membership order by token",
 *                  "parameters"={
 *                      {
 *                          "in"="path",
 *                          "name"="id",
 *                          "description"="Order token value in hashid format https://hashids.org/php/",
 *                          "required"=true,
 *                          "type"="string",
 *                          "format"="hashid"
 *                      }
 *                  }
 *              }
 *          },
 *          "send_payment_link"={
 *              "method"="PUT",
 *              "path"="/orders/memberships/{id}/payment/send",
 *              "input"=SendPaymentLinkCommand::class,
 *              "denormalization_context"={"groups"={"Default", "send_order_membership_payment_link"}},
 *              "status"=202,
 *              "output"=false,
 *              "swagger_context"={
 *                  "summary"="Send payment link for a specific membership order",
 *                  "parameters"={
 *                      {
 *                          "in"="path",
 *                          "name"="id",
 *                          "description"="Order token value in hashid format https://hashids.org/php/",
 *                          "required"=true,
 *                          "type"="string",
 *                          "format"="hashid"
 *                      }
 *                  },
 *                  "responses"={
 *                      "202"={
 *                          "description"="Payment link was sent successfully"
 *                      },
 *                      "400"={
 *                          "description"="Something went wrong while payment link sending"
 *                      },
 *                      "404"={
 *                          "description"="Order was not found"
 *                      }
 *                  }
 *              }
 *          }
 *     }
 * )
 */
class OrderMembership extends Order
{
    /**
     * @ApiProperty(identifier=false)
     */
    private $id;

    /**
     * @var Membership
     *
     * @Groups({"get_order_membership"})
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Membership")
     */
    private $membership;

    /**
     * @var string
     *
     * @ApiProperty(identifier=true)
     */
    private $token;

    public function getMembership(): Membership
    {
        return $this->membership;
    }

    public function setMembership(Membership $membership): void
    {
        $this->membership = $membership;
    }
}
