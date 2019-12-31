<?php

declare(strict_types=1);

namespace App\Domain\Admin;

use App\Domain\Club\Club;
use App\Domain\Club\ClubPartnerType;
use App\Domain\Shared\Permission;
use App\Domain\Shared\Role;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Admin
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    public const LANG_SWEDISH = 'se';
    public const LANG_ENGLISH = 'en';

    public const LANGUAGES = [
        self::LANG_SWEDISH,
        self::LANG_ENGLISH,
    ];

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(name="remember_token", type="string", nullable=true)
     */
    private $rememberToken;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    private $firstName = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    private $lastName = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     */
    private $language = self::LANG_SWEDISH;

    /**
     * @var Role[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Shared\Role", inversedBy="admins")
     */
    private $roles;

    /**
     * @var Club[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Club\Club", inversedBy="admins")
     * @ORM\JoinTable(name="admin_golf_club",
     *      joinColumns={@ORM\JoinColumn(name="admin_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="golf_club_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $clubs;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, unique=true, length=150)
     */
    private $phone = null;

    /**
     * @var Permission[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Shared\Permission")
     */
    private $permissions;

    /**
     * @var ClubPartnerType
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\ClubPartnerType")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $partnerType = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        /*$this->initializePasswordCrypter();*/

        $this->roles = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->clubs = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     *
     * @return self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param null|string $lastName
     *
     * @return self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return self
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

//    /**
//     * @return Role[]|Collection
//     */
//    public function getRoles(): Collection
//    {
//        return $this->roles;
//    }

//    /**
//     * Get the identifier that will be stored in the subject claim of the JWT.
//     *
//     * @return mixed
//     */
//    public function getJWTIdentifier()
//    {
//        return $this->getId();
//    }

//    /**
//     * Return a key value array, containing any custom claims to be added to the JWT.
//     *
//     * @return array
//     */
//    public function getJWTCustomClaims()
//    {
//        $roles = $this->getRoles()->map(function (Role $role) {
//            return $role->getName();
//        })->toArray();
//
//        return ['roles' => json_encode($roles), 'guard' => $this->getJWTGuard()];
//    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function addRole(Role $role): self
    {
        if (false === $this->roles->contains($role)) {
            $this->roles->add($role);
            $role->addAdmin($this);
        }

        return $this;
    }


    public function setRoles(array $roles): self
    {
        $this->roles = new ArrayCollection($roles);

        return $this;
    }

    /**
     * @return Club[]|Collection
     */
    public function getOrganisations(): Collection
    {
        return $this->clubs;
    }

    public function setClubs(array $clubs): self
    {
        $this->clubs =  new ArrayCollection($clubs);

        return $this;
    }

    /**
     * @return ArrayCollection|Permission[]
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    /**
     * @param Permission $permission
     *
     * @return Admin
     */
    public function addPermission(Permission $permission): self
    {
        if (false === $this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     *
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        $class = __CLASS__;

        return "{$this->id}:{$class}";
    }

    public function setPartnerType(?ClubPartnerType $partnerType): self
    {
        $this->partnerType = $partnerType;

        return $this;
    }

    public function getPartnerType(): ?ClubPartnerType
    {
        return $this->partnerType;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }
}
