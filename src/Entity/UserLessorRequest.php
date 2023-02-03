<?php

namespace App\Entity;

use App\Enums\UserLessorRequestStatus;
use App\Repository\UserLessorRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserLessorRequestRepository::class)]
class UserLessorRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'user_lessor.motivation.not_blank')]
    #[Assert\Type(type: 'string', message: 'user_lessor.motivation.type')]
    private ?string $motivation = null;

    #[ORM\Column(length: 10, options: ['default' => UserLessorRequestStatus::Pending])]
    #[Assert\Length(
        max: 10,
        maxMessage: 'user_lessor.status.max_length'
    )]
    #[Assert\Type(type: 'string', message: 'user_lessor.status.type')]
    #[Assert\Choice(
        callback: [UserLessorRequestStatus::class, 'getValues'],
        message: 'user_lessor.status.choice'
    )]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Type(type: 'string', message: 'user_lessor.refusing_reason.type')]
    private ?string $refusing_reason = null;

    #[ORM\ManyToOne(inversedBy: 'userLessorRequests')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    #[Assert\Type(type: User::class)]
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

    public function getStatus(): ?UserLessorRequestStatus
    {
        return UserLessorRequestStatus::from($this->status);
    }

    public function setStatus(UserLessorRequestStatus $status): self
    {
        $this->status = $status->value;

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
