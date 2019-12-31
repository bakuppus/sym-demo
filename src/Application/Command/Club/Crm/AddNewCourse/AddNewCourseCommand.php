<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\AddNewCourse;

use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use App\Domain\Club\Club;
use App\Domain\Course\Course;
use App\Infrastructure\Shared\Doctrine\Type\Spatial\Point;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class AddNewCourseCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var string
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @var string
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $description;

    /**
     * @var string
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $bookingInformation;

    /**
     * @var string
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $customDescription;

    /**
     * @var string
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $customBookingInformation;

    /**
     * @var string
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $customDescriptionShort;

    /**
     * @var string
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $customBookingInformationShort;

    /**
     * @var bool
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $isUseCustomInformation;

    /**
     * @var bool
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $isActive;

    /**
     * @var bool
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $isUseDynamicPricing;

    /**
     * @var float
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $longitude;

    /**
     * @var float
     *
     * @Groups({"add_new_course"})
     * @Assert\NotBlank
     */
    public $latitude;

    /**
     * @return Course|object
     * @throws Exception
     */
    public function getResource(): object
    {
        $resource = new Course();
        $resource->setClub($this->getObjectToPopulate());
        $resource->setName($this->name);
        $resource->setDescription($this->description);
        $resource->setBookingInformation($this->bookingInformation);
        $resource->setCustomDescription($this->customDescription);
        $resource->setCustomBookingInformation($this->customBookingInformation);
        $resource->setIsUseCustomInformation($this->isUseCustomInformation);
        $resource->setCustomDescriptionShort($this->customDescriptionShort);
        $resource->setCustomBookingInformationShort($this->customBookingInformationShort);
        $resource->setIsActive($this->isActive);
        $resource->setIsUseDynamicPricing($this->isUseDynamicPricing);
        $resource->setBookingType(1);
        $resource->setTeeTimeSource(Course::SOURCE_SWEETSPOT);
        $resource->setCreatedAt(new \DateTime());
        $resource->setUpdatedAt(new \DateTime());
        $resource->setLonlat(new Point($this->longitude, $this->latitude));

        return $resource;
    }

    /**
     * @return Club|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
