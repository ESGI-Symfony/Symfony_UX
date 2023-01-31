<?php

namespace App\Entity;

use App\Repository\UserLessorRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLessorRequestRepository::class)]
class UserLessorRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motivation = null;

    #[ORM\Column(length: 10)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $refusing_reason = null;

    #[ORM\ManyToOne(inversedBy: 'userLessorRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $lessor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(string $motivation): self
    {
        $this->motivation = $motivation;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRefusingReason(): ?string
    {
        return $this->refusing_reason;
    }

    public function setRefusingReason(string $refusing_reason): self
    {
        $this->refusing_reason = $refusing_reason;

        return $this;
    }

    public function getLessor(): ?user
    {
        return $this->lessor;
    }

    public function setLessor(?user $lessor): self
    {
        $this->lessor = $lessor;

        return $this;
    }

}
