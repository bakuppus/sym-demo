<?php

declare(strict_types=1);

namespace App\Domain\Admin;

use App\Domain\Shared\Permission;
use App\Domain\Shared\Role;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * Class SuperAdmin
 *
 * @package App\DAO\Entities
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class
SuperAdmin
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
     * @var Role[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Shared\Role", inversedBy="superAdmins")
     */
    private $roles;

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
     * @ORM\Column(type="string", unique=true, length=150)
     */
    protected $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    protected $firstName = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=150)
     */
    protected $lastName = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, unique=true, length=150)
     */
    protected $phone = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $language = self::LANG_SWEDISH;

    /**
     * @var Permission[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Domain\Shared\Permission")
     */
    protected $permissions;

    /**
     * SuperAdmin constructor.
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
//        $this->initializePasswordCrypter();
        $this->roles = new ArrayCollection();
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
     * @return SuperAdmin
     */
    public function setEmail(string $email): SuperAdmin
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Role[]|Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = new ArrayCollection($roles);

        return $this;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function addRole(Role $role)
    {
        if (false === $this->roles->contains($role)) {
            $this->roles->add($role);
            $role->addSuperAdmin($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     *
     * @return SuperAdmin
     */
    public function setFirstName(?string $firstName): SuperAdmin
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
     * @return SuperAdmin
     */
    public function setLastName(?string $lastName): SuperAdmin
    {
        $this->lastName = $lastName;

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
     * @return SuperAdmin
     */
    public function setPhone(?string $phone): SuperAdmin
    {
        $this->phone = $phone;

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
     * @return SuperAdmin
     */
    public function setLanguage(string $language): SuperAdmin
    {
        $this->language = $language;

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

    public function addPermission(Permission $permission): self
    {
        if (false === $this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }

    /**
     * @return Collection|Permission[]
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
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
