<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\Length(
     *      min = 2,
     *      max = 150,
     *      minMessage = "Le Nom doit etre supérieur a 5 caracteres",
     *      maxMessage = "Le Nom doit etre inférieur a 150 caracteres"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotCompromisedPassword(
     *     message="Mot De Passe non-securisé"
     *  )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Assert\Email(
     *     message="Email Invalide"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/\d{9}/",
     *     message="Numéro de Telephone erroné"
     * )
     */
    private $mobile_phone;

    /**
     * @ORM\Column(type="float",  nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="float",  nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $logoName;

    /**
     * @ORM\OneToOne(targetEntity=Inventories::class, mappedBy="companyName", cascade={"persist", "remove"})
     */
    private $inventory;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="users")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="users")
     */
    private $zone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activated;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }




    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(string $logoName): self
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function getInventory(): ?Inventories
    {
        return $this->inventory;
    }

    public function setInventory(Inventories $inventory): self
    {
        // set the owning side of the relation if necessary
        if ($inventory->getCompanyName() !== $this) {
            $inventory->setCompanyName($this);
        }

        $this->inventory = $inventory;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMobilePhone()
    {
        return $this->mobile_phone;
    }

    /**
     * @param mixed $mobile_phone
     */
    public function setMobilePhone($mobile_phone): void
    {
        $this->mobile_phone = $mobile_phone;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }
}
