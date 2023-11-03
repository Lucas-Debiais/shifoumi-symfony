<?php

namespace App\Entity;

use App\Enum\Choices;
use App\Repository\PartyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartyRepository::class)]
#[ORM\Table(name: 'parties')]

class Party
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player1 = null;

    #[ORM\ManyToOne()]
    private ?User $player2 = null;

    #[ORM\Column(type: "string", enumType: Choices::class)]
    private Choices $choice1;

    #[ORM\Column(type: "string", enumType: Choices::class)]
    private Choices $choice2;

    #[ORM\ManyToOne(inversedBy: 'win')]
    private ?User $whoWin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer1(): ?User
    {
        return $this->player1;
    }

    public function setPlayer1(User $player1): static
    {
        $this->player1 = $player1;

        return $this;
    }

    public function getPlayer2(): ?User
    {
        return $this->player2;
    }

    public function setPlayer2(?User $player2): static
    {
        $this->player2 = $player2;

        return $this;
    }

    public function getChoice1() :Choices
    {
        return $this->choice1;
    }

    public function setChoice1(Choices $choice1): static
    {
        $this->choice1 = $choice1;

        return $this;
    }

    public function getChoice2(): Choices
    {
        return $this->choice2;
    }

    public function setChoice2(Choices $choice2): static
    {
        $this->choice2 = $choice2;

        return $this;
    }

    public function getWhoWin(): ?User
    {
        return $this->whoWin;
    }

    public function setWhoWin(?User $whoWin): static
    {
        $this->whoWin = $whoWin;

        return $this;
    }
}
