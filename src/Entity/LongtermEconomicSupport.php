<?php

namespace App\Entity;

use App\Repository\LongtermEconomicSupportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LongtermEconomicSupportRepository::class)]
class LongtermEconomicSupport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\Column(name: "group_name", length: 255)]
    private ?string $groupName = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?float $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(string $groupName): static
    {
        $this->groupName = $groupName;
        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;
        return $this;
    }
}
