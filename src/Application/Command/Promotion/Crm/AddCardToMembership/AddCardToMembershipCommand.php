<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddCardToMembership;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Domain\Player\Player;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Promotion\Validator\CanAddMembershipCard;
use App\Infrastructure\Promotion\Validator\HasPlayRight;
use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @CanAddMembershipCard(groups={"add_card_to_membership"})
 * @HasPlayRight(groups={"add_card_to_membership"})
 */
final class AddCardToMembershipCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var int|Player
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="int",
     *             "example"=1
     *         }
     *     }
     * )
     *
     * @Groups({"add_card_to_membership"})
     *
     * @Assert\NotBlank(groups={"add_card_to_membership"})
     * @Assert\Type("integer", groups={"add_card_to_membership"})
     *
     * @CommandBind(targetEntity="App\Domain\Player\Player")
     */
    public $player;

    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "enum"={"annual_duration", "12_month"},
     *             "example"="annual_duration"
     *         }
     *     }
     * )
     *
     * @Groups({"add_card_to_membership"})
     *
     * @Assert\Type("string", groups={"add_card_to_membership"})
     * @Assert\Expression(
     *     "this.durationType in this.objectToPopulate.getDurationOptions()",
     *     message="Invalid duration type",
     *     groups={"add_card_to_membership"}
     * )
     * @Assert\Expression(
     *     "this.durationType === constant('App\\Domain\\Promotion\\Membership::DURATION_12_MONTH') || null !== this.calendarYear",
     *     message="Annual duration requires calendar_year field",
     *     groups={"add_card_to_membership"}
     * )
     */
    public $durationType;

    /**
     * @var string|null
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="2020-01-01"
     *         }
     *     }
     * )
     *
     * @Groups({"add_card_to_membership"})
     *
     * @Assert\Type("string", groups={"add_card_to_membership"})
     * @Assert\Expression(
     *     "null === this.calendarYear || this.durationType === constant('App\\Domain\\Promotion\\Membership::DURATION_ANNUAL')",
     *     message="This option is available only for annual duration",
     *     groups={"add_card_to_membership"}
     * )
     */
    public $calendarYear;

    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="bool",
     *             "example"=true
     *         }
     *     }
     * )
     *
     * @Groups({"add_card_to_membership"})
     *
     * @Assert\Type("boolean", groups={"add_card_to_membership"})
     * @Assert\NotNull(groups={"add_card_to_membership"})
     */
    public $isSendPaymentLink;

    /**
     * @throws Exception
     */
    public function getResource(): object
    {
        $resource = new MembershipCard();
        $resource->setMembership($this->getObjectToPopulate());
        $resource->setClub($this->getObjectToPopulate()->getClub());
        $resource->setPlayer($this->player);
        $resource->setDurationType($this->durationType);
        $resource->setCalendarYearFromString($this->calendarYear);
        $resource->setIsSweetspot(true);

        return $resource;
    }

    /**
     * @return Membership|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
