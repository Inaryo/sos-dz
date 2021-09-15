<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Catastrophe::class, mappedBy="besoins")
     */
    private $catastrophes;

    public function __construct()
    {
        $this->catastrophes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Catastrophe[]
     */
    public function getCatastrophes(): Collection
    {
        return $this->catastrophes;
    }

    public function addCatastrophe(Catastrophe $catastrophe): self
    {
        if (!$this->catastrophes->contains($catastrophe)) {
            $this->catastrophes[] = $catastrophe;
            $catastrophe->addBesoin($this);
        }

        return $this;
    }

    public function removeCatastrophe(Catastrophe $catastrophe): self
    {
        if ($this->catastrophes->removeElement($catastrophe)) {
            $catastrophe->removeBesoin($this);
        }

        return $this;
    }
}
