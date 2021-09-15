<?php

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanRepository::class)
 */
class Plan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Catastrophe::class, inversedBy="plans")
     */
    private $catastrophe;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="plans")
     */
    private $zone;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activate;

    /**
     * @ORM\Column(type="json")
     */
    private $besoins = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatastrophe(): ?Catastrophe
    {
        return $this->catastrophe;
    }

    public function setCatastrophe(?Catastrophe $catastrophe): self
    {
        $this->catastrophe = $catastrophe;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getActivate(): ?bool
    {
        return $this->activate;
    }

    public function setActivate(bool $activate): self
    {
        $this->activate = $activate;

        return $this;
    }

    public function getBesoins(): ?array
    {
        return $this->besoins;
    }

    public function setBesoins(array $besoins): self
    {
        $this->besoins = $besoins;

        return $this;
    }
}
