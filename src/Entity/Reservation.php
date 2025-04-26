<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $checkInDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $checkOutDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalPrice = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $specialRequests = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'reservation', targetEntity: ReservationRoom::class, orphanRemoval: true)]
    private Collection $reservationRooms;

    public function __construct()
    {
        $this->reservationRooms = new ArrayCollection();
    }

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

    public function getCheckInDate(): ?\DateTimeInterface
    {
        return $this->checkInDate;
    }

    public function setCheckInDate(\DateTimeInterface $checkInDate): static
    {
        $this->checkInDate = $checkInDate;
        return $this;
    }

    public function getCheckOutDate(): ?\DateTimeInterface
    {
        return $this->checkOutDate;
    }

    public function setCheckOutDate(\DateTimeInterface $checkOutDate): static
    {
        $this->checkOutDate = $checkOutDate;
        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getSpecialRequests(): ?string
    {
        return $this->specialRequests;
    }

    public function setSpecialRequests(?string $specialRequests): static
    {
        $this->specialRequests = $specialRequests;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return Collection<int, ReservationRoom>
     */
    public function getReservationRooms(): Collection
    {
        return $this->reservationRooms;
    }

    public function addReservationRoom(ReservationRoom $reservationRoom): static
    {
        if (!$this->reservationRooms->contains($reservationRoom)) {
            $this->reservationRooms->add($reservationRoom);
            $reservationRoom->setReservation($this);
        }

        return $this;
    }

    public function removeReservationRoom(ReservationRoom $reservationRoom): static
    {
        if ($this->reservationRooms->removeElement($reservationRoom)) {
            // set the owning side to null (unless already changed)
            if ($reservationRoom->getReservation() === $this) {
                $reservationRoom->setReservation(null);
            }
        }

        return $this;
    }
} 
