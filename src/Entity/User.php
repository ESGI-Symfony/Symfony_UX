<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'user.email.not_blank')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'user.email.max_length'
    )]
    #[Assert\Email(message: 'user.email.type')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'user.nickname.not_blank')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'user.nickname.max_length'
    )]
    #[Assert\Type(type: 'string', message: 'user.nickname.type')]
    private ?string $nickname = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'user.password.max_length'
    )]
    #[Assert\Type(type: 'string', message: 'user.password.type')]
    private ?string $password = null;

    private ?string $plainPassword = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    #[Assert\Type(type: 'boolean', message: 'user.password.type')]
    private bool $isVerified = false;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Type(type: 'string', message: 'user.firstname.type')]
    #[Assert\Length(
        max: 150,
        maxMessage: 'user.firstname.max_length'
    )]
    #[Assert\NotBlank(message: 'user.firstname.not_blank', groups: ['lessor'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Type(type: 'string', message: 'user.lastname.type')]
    #[Assert\Length(
        max: 150,
        maxMessage: 'user.lastname.max_length'
    )]
    #[Assert\NotBlank(message: 'user.lastname.not_blank', groups: ['lessor'])]
    private ?string $lastname = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(type: 'number', message: 'user.lessor_number.type')]
    #[Assert\NotBlank(message: 'user.firstname.not_blank', groups: ['lessor'])]
    private ?int $lessor_number = null;

    #[ORM\OneToMany(mappedBy: 'lessor', targetEntity: UserLessorRequest::class, orphanRemoval: true)]
    private Collection $userLessorRequests;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Rental::class, orphanRemoval: true)]
    private Collection $rentals;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Review::class)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'buyer', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Report::class)]
    private Collection $reports;

    #[ORM\Column(length: 42, nullable: true)]
    #[Assert\NotBlank(message: 'user.phone.not_blank', groups: ['lessor'])]
    private ?string $phone = null;


    public function __construct()
    {
        $this->userLessorRequests = new ArrayCollection();
        $this->rentals = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLessorNumber(): ?int
    {
        return $this->lessor_number;
    }

    public function setLessorNumber(?int $lessor_number): self
    {
        $this->lessor_number = $lessor_number;

        return $this;
    }

    /**
     * @return Collection<int, UserLessorRequest>
     */
    public function getUserLessorRequests(): Collection
    {
        return $this->userLessorRequests;
    }

    public function addUserLessorRequest(UserLessorRequest $userLessorRequest): self
    {
        if (!$this->userLessorRequests->contains($userLessorRequest)) {
            $this->userLessorRequests->add($userLessorRequest);
            $userLessorRequest->setLessor($this);
        }

        return $this;
    }

    public function removeUserLessorRequest(UserLessorRequest $userLessorRequest): self
    {
        if ($this->userLessorRequests->removeElement($userLessorRequest)) {
            // set the owning side to null (unless already changed)
            if ($userLessorRequest->getLessor() === $this) {
                $userLessorRequest->setLessor(null);
            }
        }

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
            $rental->setOwner($this);
        }

        return $this;
    }

    public function removeRental(Rental $rental): self
    {
        if ($this->rentals->removeElement($rental)) {
            // set the owning side to null (unless already changed)
            if ($rental->getOwner() === $this) {
                $rental->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setAuthor($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
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
            $reservation->setBuyer($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getBuyer() === $this) {
                $reservation->setBuyer(null);
            }
        }

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
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
            $report->setAuthor($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getAuthor() === $this) {
                $report->setAuthor(null);
            }
        }

        return $this;
    }

    public function isLessor(): bool
    {
        return in_array('ROLE_LESSOR', $this->getRoles());
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
