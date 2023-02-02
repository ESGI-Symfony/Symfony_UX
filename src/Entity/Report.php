<?php

namespace App\Entity;

use App\Enums\ReportTypes;
use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'report.comment.not_blank')]
    #[Assert\Type(type: 'string', message: 'report.comment.type')]
    #[Assert\Length(
        max: 10000,
        maxMessage: 'report.comment.max_length'
    )]
    private ?string $comment = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'report.type.not_blank')]
    #[Assert\Type(type: 'string', message: 'report.type.type')]
    #[Assert\Length(
        max: 20,
        maxMessage: 'report.type.max_length'
    )]
    #[Assert\Choice(
        callback: [ReportTypes::class, 'getValues'],
        message: 'report.type.choice'
    )]
    private ?ReportTypes $type = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rental $rental = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getType(): ?ReportTypes
    {
        return $this->type;
    }

    public function setType(ReportTypes $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRental(): ?Rental
    {
        return $this->rental;
    }

    public function setRental(?Rental $rental): self
    {
        $this->rental = $rental;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
