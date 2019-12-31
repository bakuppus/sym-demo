<?php

declare(strict_types=1);

namespace App\Domain\Player;

use Gedmo\Loggable\Entity\LogEntry as GedmoLogEntry;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="ext_log_entries",
 *     options={"row_format":"DYNAMIC"},
 *  indexes={
 *      @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *      @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})
 *  }
 * )
 * @ORM\Entity
 */
class PlayerLogEntry extends GedmoLogEntry
{
}
