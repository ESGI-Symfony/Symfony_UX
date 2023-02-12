<?php

namespace App\Entity;

use App\Enums\RentalTypes;
use App\Repository\RentalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: RentalRepository::class)]
#[Uploadable]
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
    #[Assert\PositiveOrZero(message: 'rental.room_count.positive')]
    private ?int $room_count = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'rental.bathroom_count.not_null')]
    #[Assert\Type(type: 'int', message: 'rental.bathroom_count.type')]
    #[Assert\PositiveOrZero(message: 'rental.bathroom_count.positive')]
    private ?int $bathroom_count = null;

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
    private ?string $rent_type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'rental.celestial_object.not_blank')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'rental.celestial_object.max_length'
    )]
    #[Assert\Type(type: 'string', message: 'rental.celestial_object.type')]
    private ?string $celestial_object = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'rental.longitude.not_null')]
    #[Assert\Type(type: 'float', message: 'rental.longitude.type')]
    #[Assert\Range(
        notInRangeMessage: 'rental.longitude.range',
        min: -180,
        max: 180,
    )]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'rental.latitude.not_null')]
    #[Assert\Type(type: 'float', message: 'rental.latitude.type')]
    #[Assert\Range(
        notInRangeMessage: 'rental.latitude.range',
        min: -90,
        max: 90,
    )]
    private ?float $latitude = null;

    #[ORM\ManyToMany(targetEntity: Transport::class, inversedBy: 'rentals')]
    private Collection $transports;

    private ?string $image = null;
    #[UploadableField(mapping: 'rentals', fileNameProperty: 'image')]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png'],
        maxSizeMessage: 'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.',
        mimeTypesMessage: 'Please upload a png or jpeg image',
    )]
    #[Assert\NotNull(message: 'rental.image.not_null')]
    private ?File $imageFile = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    private float $sum_rating = 0;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->transports = new ArrayCollection();
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

    public function getRentType(): ?RentalTypes
    {
        return $this->rent_type ? RentalTypes::from($this->rent_type) : null;
    }

    public function setRentType(RentalTypes|string $rent_type): self
    {
        if (is_string($rent_type)) {
            $rent_type = RentalTypes::from($rent_type);
        }
        $this->rent_type = $rent_type->value;

        return $this;
    }

    public function getSystem(): ?string
    {
        return $this->system;
    }

    public function setSystem(string $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getCelestialObject(): ?string
    {
        return $this->celestial_object;
    }

    public function setCelestialObject(string $celestial_object): self
    {
        $this->celestial_object = $celestial_object;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return Collection<int, Transport>
     */
    public function getTransports(): Collection
    {
        return $this->transports;
    }

    public function addTransport(Transport $transport): self
    {
        if (!$this->transports->contains($transport)) {
            $this->transports->add($transport);
        }

        return $this;
    }

    public function removeTransport(Transport $transport): self
    {
        $this->transports->removeElement($transport);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getSumRating(): ?float
    {
        return $this->sum_rating;
    }

    public function setSumRating(?float $sumRating): self
    {
        // round to half
        $this->sum_rating = $sumRating ? round($sumRating*2)/2 : 0;
        return $this;
    }
}
