<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\CreateClub;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Domain\Club\Club;
use App\Infrastructure\Shared\Doctrine\Type\Spatial\PointFactory;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhone;

final class CreateClubCommand implements CommandAwareInterface
{
    /**
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Club name"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(min="3", max="150")
     * @Assert\Type(type="string")
     */
    public $name;

    /**
     * @SerializedName("git_id")
     *
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string|uuid",
     *             "example"="5161ec75-8c80-48d4-b804-706d6e12ce78"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Uuid
     * @Assert\Type(type="string")
     */
    public $gitId;

    /**
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="float",
     *             "example"=44.44
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Range(min="-180", max="180")
     * @Assert\Type(type="float")
     */
    public $longitude;

    /**
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="float",
     *             "example"=-37.505069055
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Range(min="-90", max="90")
     * @Assert\Type(type="float")
     */
    public $latitude;

    /**
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="+46766920976"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @AssertPhone
     * @Assert\Type(type="string")
     */
    public $phone;

    /**
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="support@club.com"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Email
     * @Assert\Length(min="0", max="150")
     * @Assert\Type(type="string")
     */
    public $email;

    /**
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="https://club.com"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Url
     * @Assert\Length(min="0", max="150")
     * @Assert\Type(type="string")
     */
    public $website;

    /**
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Test description"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(min="0", max="1000")
     * @Assert\Type(type="string")
     */
    public $description;

    /**
     * @SerializedName("description_short")
     *
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Test short description"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(min="0", max="250")
     * @Assert\Type(type="string")
     */
    public $descriptionShort;

    /**
     * @SerializedName("booking_information")
     *
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Booking information"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(min="0", max="1000")
     * @Assert\Type(type="string")
     */
    public $bookingInformation;

    /**
     * @SerializedName("booking_information_short")
     *
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Booking short information"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(min="0", max="250")
     * @Assert\Type(type="string")
     */
    public $bookingInformationShort;

    /**
     * @SerializedName("is_sync_with_git")
     *
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="bool",
     *             "example"=true
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank
     * @Assert\Type(type="bool")
     */
    public $isSyncWithGit;

    /**
     * @SerializedName("is_admin_assure_bookable")
     *
     * @Groups({"create_club"})
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="bool",
     *             "example"=true
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank
     * @Assert\Type(type="bool")
     */
    public $isAdminAssureBookable;

    /**
     * @return Club|object
     * @throws Exception
     */
    public function getResource(): object
    {
        $point = (new PointFactory())->create($this->longitude, $this->latitude);

        $resource = new Club();
        $resource
            ->setName($this->name)
            ->setGitId($this->gitId)
            ->setPhone($this->phone)
            ->setEmail($this->email)
            ->setWebsite($this->website)
            ->setDescription($this->description)
            ->setDescriptionShort($this->descriptionShort)
            ->setBookingInformation($this->bookingInformation)
            ->setBookingInformationShort($this->bookingInformationShort)
            ->setIsSyncWithGit($this->isSyncWithGit)
            ->setIsAdminAssureBookable($this->isAdminAssureBookable)
            ->setLonlat($point);

        return $resource;
    }
}
