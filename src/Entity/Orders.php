<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrdersRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @JoinColumn(nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $ordered_at;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="orders")
     */
    private $reserve;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="nb_participant")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="Orders")
     */
    private $reservations;

    public function __construct()
    {
        $this->reserve = new ArrayCollection();
        $this->orderEvents = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderedAt(): ?\DateTimeImmutable
    {
        return $this->ordered_at;
    }

    public function setOrderedAt(\DateTimeImmutable $ordered_at): self
    {
        $this->ordered_at = $ordered_at;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getReserve(): Collection
    {
        return $this->reserve;
    }

    public function addReserve(Event $reserve): self
    {
        if (!$this->reserve->contains($reserve)) {
            $this->reserve[] = $reserve;
        }

        return $this;
    }

    public function removeReserve(Event $reserve): self
    {
        $this->reserve->removeElement($reserve);

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
            $this->reservations[] = $reservation;
            $reservation->setOrders($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getOrders() === $this) {
                $reservation->setOrders(null);
            }
        }

        return $this;
    }

    
}
