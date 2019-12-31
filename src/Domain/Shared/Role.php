<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use App\Domain\Admin\Admin;
use App\Domain\Admin\SuperAdmin;
use App\Domain\Player\Player;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity()
 */
class Role /*implements EntityAwareInterface, RoleInterface*/
{
    public const ROLE_SUPER_ADMIN = 'SA';
    public const ROLE_CLUB_ADMIN = 'CA';
    public const ROLE_PLAYER = 'PLAYER';
    public const ROLE_PARTNER = 'PARTNER';

    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var Admin[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Admin\Admin", mappedBy="roles")
     */
    protected $admins;

    /**
     * @var SuperAdmin[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Admin\SuperAdmin", mappedBy="roles")
     */
    protected $superAdmins;

    /**
     * @var Player[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Player\Player", mappedBy="roles")
     */
    protected $players;

    /**
     * @var Permission[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Shared\Permission")
     */
    protected $permissions;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->admins = new ArrayCollection();
        $this->superAdmins = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Admin[]|Collection
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    /**
     * @return SuperAdmin[]|Collection
     */
    public function getSuperAdmins(): Collection
    {
        return $this->superAdmins;
    }

    public function addSuperAdmin(SuperAdmin $superAdmin): self
    {
        if (false === $this->superAdmins->contains($superAdmin)) {
            $this->superAdmins->add($superAdmin);
//            $superAdmin->addRole($this);
        }

        return $this;
    }

    /**
     * @return Player[]|ArrayCollection|Collection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (false === $this->players->contains($player)) {
            $this->players->add($player);
//            $player->addRole($this);
        }

        return $this;
    }

    /**
     * @param Admin $admin
     *
     * @return $this
     */
    public function addAdmin(Admin $admin): self
    {
        if (false === $this->admins->contains($admin)) {
            $this->admins->add($admin);
//            $admin->addRole($this);
        }

        return $this;
    }

    public function addPermission(Permission $permission): self
    {
        if (false === $this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }
}
