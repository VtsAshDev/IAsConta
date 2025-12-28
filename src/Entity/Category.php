<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Category
{
    private ?int $id = null;
    private string $name;
    private ?string $icon = null;
    private ?Collection $expenses;

    public function getId(): int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): Category
    {
        $this->icon = $icon;
        return $this;
    }

    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function setExpenses(Collection $expenses): Category
    {
        $this->expenses = $expenses;
        return $this;
    }

}
