<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Doctrine\Type\Spatial;

use CrEOF\Spatial\PHP\Types\Geometry\Point as PointGeometry;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class Point extends PointGeometry
{
    /**
     * @var $x
     *
     * @SerializedName("lon")
     */
    protected $x;

    /**
     * @var $y
     *
     * @SerializedName("lat")
     */
    protected $y;

    public function __construct()
    {
        $args = func_get_args();
        if (false === empty($args)) {
            parent::__construct(...$args);
        }
    }

    /**
     * @Groups({"get_club", "list_club", "list_course"})
     */
    public function getLatitude()
    {
        return parent::getLatitude();
    }

    /**
     * @Groups({"get_club", "list_club", "list_course"})
     */
    public function getLongitude()
    {
        return parent::getLongitude();
    }

    public function toArray()
    {
        return [
            'lat' => $this->getLatitude(),
            'lon' => $this->getLongitude(),
        ];
    }
}
