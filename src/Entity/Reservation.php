<?php

namespace App\Entity;

use App\Entity\Traits\CustomTimestampableTrait;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    use CustomTimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'reservation.date_begin.not_blank')]
    #[Assert\Type(type: 'DateTimeInterface', message: 'reservation.date_begin.date')]
    private ?\DateTimeInterface $date_begin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'reservation.date_end.not_blank')]
    #[Assert\Type(type: 'DateTimeInterface', message: 'reservation.date_end.date')]
    #[Assert\Expression(
        expression: 'this.getDateBegin() < value',
        message: 'reservation.date_end.greater_than'
    )]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'reservations.payment_token.max_length'
    )]
    #[Assert\Type(type: 'string', message: 'reservation.payment_token.type')]
    private ?string $payment_token = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rental $rental = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $buyer = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(type: 'integer', message: 'reservation.review_mark.type')]
    #[Assert\Range(
        notInRangeMessage: 'reservation.review_mark.range',
        min: 1,
        max: 5,
    )]
    private ?int $review_mark = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Type(type: 'string', message: 'reservation.review_comment.type')]
    private ?string $review_comment = null;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateBegin(): ?\DateTimeInterface
    {
        return $this->date_begin;
    }

    public function setDateBegin(\DateTimeInterface $date_begin): self
    {
        $this->date_begin = $date_begin;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getPaymentToken(): ?string
    {
        return $this->payment_token;
    }

    public function setPaymentToken(?string $payment_token): self
    {
        $this->payment_token = $payment_token;

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

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getReviewMark(): ?int
    {
        return $this->review_mark;
    }

    public function setReviewMark(?int $review_mark): self
    {
        $this->review_mark = $review_mark;

        return $this;
    }

    public function getReviewComment(): ?string
    {
        return $this->review_comment;
    }

    public function setReviewComment(?string $review_comment): self
    {
        $this->review_comment = $review_comment;

        return $this;
    }

    public function getTotalPrice(): float
    {
        $interval = $this->getDateBegin()->diff($this->getDateEnd());
        $days = $interval->format('%a');
        return $this->getRental()->getPrice() * $days;
    }
}
