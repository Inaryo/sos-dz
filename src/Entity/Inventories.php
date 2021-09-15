<?php

namespace App\Entity;

use App\Repository\InventoriesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InventoriesRepository::class)
 */
class Inventories
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="inventory", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $companyName;

    /**
     * @ORM\Column(type="json")
     */
    private $content = [];



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?User
    {
        return $this->companyName;
    }

    public function setCompanyName(User $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }


}
