<?php

namespace App\Entity;

use App\Enums\RentalTypes;
use App\Repository\RentalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RentalRepository::class)]
class Rental
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'rental.description.not_blank')]
    #[Assert\Length(
        max: 10000,
        maxMessage: 'rental.description.max_length'
    )]
    #[Assert\Type(type: 'string', message: 'rental.description.type')]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'rental.price.not_null')]
    #[Assert\Type(type: 'float', message: 'rental.price.type')]
    #[Assert\Positive(message: 'rental.price.positive')]
    private ?float $price = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'rental.max_capacity.not_null')]
    #[Assert\Type(type: 'int', message: 'rental.max_capacity.type')]
    #[Assert\Positive(message: 'rental.max_capacity.positive')]
    private ?int $max_capacity = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'rental.room_count.not_null')]
    #[Assert\Type(type: 'int', message: 'rental.room_count.type')]
    #[Assert\Positive(message: 'rental.room_count.positive')]
    private ?int $room_count = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'rental.bathroom_count.not_null')]
    #[Assert\Type(type: 'int', message: 'rental.bathroom_count.type')]
    #[Assert\Positive(message: 'rental.bathroom_count.positive')]
    private ?int $bathroom_count = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'rental.date_begin.not_blank')]
    #[Assert\Date(message: 'rental.date_begin.date')]
    private ?\DateTimeInterface $date_begin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'rental.date_end.not_blank')]
    #[Assert\Date(message: 'rental.date_end.date')]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\ManyToOne(inversedBy: 'rentals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;

    #[ORM\ManyToOne(inversedBy: 'rentals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $owner = null;

    #[ORM\ManyToMany(targetEntity: RentalOption::class, inversedBy: 'rentals')]
    private Collection $options;

    #[ORM\OneToMany(mappedBy: 'rental', targetEntity: Report::class, orphanRemoval: true)]
    private Collection $reports;

    #[ORM\OneToMany(mappedBy: 'rental', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'rental.type.not_blank')]
    #[Assert\Type(type: 'string', message: 'rental.type.type')]
    #[Assert\Length(
        max: 20,
        maxMessage: 'rental.type.max_length'
    )]
    #[Assert\Choice(
        callback: [RentalTypes::class, 'getValues'],
        message: 'rental.type.choice'
    )]
    private ?RentalTypes $rent_type = null;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMaxCapacity(): ?int
    {
        return $this->max_capacity;
    }

    public function setMaxCapacity(int $max_capacity): self
    {
        $this->max_capacity = $max_capacity;

        return $this;
    }

    public function getRoomCount(): ?int
    {
        return $this->room_count;
    }

    public function setRoomCount(int $room_count): self
    {
        $this->room_count = $room_count;

        return $this;
    }

    public function getBathroomCount(): ?int
    {
        return $this->bathroom_count;
    }

    public function setBathroomCount(int $bathroom_count): self
    {
        $this->bathroom_count = $bathroom_count;

        return $this;
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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOwner(): ?user
    {
        return $this->owner;
    }

    public function setOwner(?user $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, RentalOption>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(RentalOption $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
        }

        return $this;
    }

    public function removeOption(RentalOption $option): self
    {
        $this->options->removeElement($option);

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setRental($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getRental() === $this) {
                $report->setRental(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setRental($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getRental() === $this) {
                $reservation->setRental(null);
            }
        }

        return $this;
    }

    public function getRentType(): ?string
    {
        return $this->rent_type;
    }

    public function setRentType(string $rent_type): self
    {
        $this->rent_type = $rent_type;

        return $this;
    }
}
