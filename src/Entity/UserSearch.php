<?php

namespace App\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;


class UserSearch
{
    /**
     * @var Zone|null
     */
    private $zone;

    /**
     * @var Category|null
     */
    private $category;

    /**
     * @return Zone|null
     */
    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    /**
     * @param Zone|null $zone
     */
    public function setZone(?Zone $zone): void
    {
        $this->zone = $zone;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }


}
