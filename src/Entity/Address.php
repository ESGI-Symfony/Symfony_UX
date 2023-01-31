<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'address.system.not_blank')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'address.system.max_length'
    )]
    #[Assert\Type(type: 'string', message: 'address.system.type')]
    private ?string $system = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'address.celestial_object.not_blank')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'address.celestial_object.max_length'
    )]
    #[Assert\Type(type: 'string', message: 'address.celestial_object.type')]
    private ?string $celestial_object = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'address.longitude.not_null')]
    #[Assert\Type(type: 'float', message: 'address.longitude.type')]
    #[Assert\Range(
        notInRangeMessage: 'address.longitude.range',
        min: -180,
        max: 180,
    )]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'address.latitude.not_null')]
    #[Assert\Type(type: 'float', message: 'address.latitude.type')]
    #[Assert\Range(
        notInRangeMessage: 'address.latitude.range',
        min: -90,
        max: 90,
    )]
    private ?float $latitude = null;

    #[ORM\ManyToMany(targetEntity: Transport::class, inversedBy: 'addresses')]
    private Collection $transports;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Rental::class)]
    private Collection $rentals;

    public function __construct()
    {
        $this->transports = new ArrayCollection();
        $this->rentals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, Rental>
     */
    public function getRentals(): Collection
    {
        return $this->rentals;
    }

    public function addRental(Rental $rental): self
    {
        if (!$this->rentals->contains($rental)) {
            $this->rentals->add($rental);
            $rental->setAddress($this);
        }

        return $this;
    }

    public function removeRental(Rental $rental): self
    {
        if ($this->rentals->removeElement($rental)) {
            // set the owning side to null (unless already changed)
            if ($rental->getAddress() === $this) {
                $rental->setAddress(null);
            }
        }

        return $this;
    }
}
