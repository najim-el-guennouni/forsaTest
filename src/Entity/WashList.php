<?php

namespace App\Entity;

use App\Repository\WashListRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WashListRepository::class)]
class WashList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'washLists')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'washLists')]
    private ?Cours $cours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }
}
