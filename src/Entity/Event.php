<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_start;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_end;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="reserve") 
     * @JoinColumn(nullable=true, onDelete="SET NULL")     
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="events")
     */
    private $categorie_event;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="event",orphanRemoval=true)
     */
    private $comments;

    

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $artist;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $cp_location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city_location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_location;

    /**
     * @ORM\ManyToMany(targetEntity=Orders::class, mappedBy="reserve")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="Event")
     */
    private $reservations;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_place;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->categorie_event = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->event_by_artist = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->orderEvents = new ArrayCollection();
        $this->nb_participant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addReserve($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeReserve($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategorieEvent(): Collection
    {
        return $this->categorie_event;
    }

    public function addCategorieEvent(Category $categorieEvent): self
    {
        if (!$this->categorie_event->contains($categorieEvent)) {
            $this->categorie_event[] = $categorieEvent;
        }

        return $this;
    }

    public function removeCategorieEvent(Category $categorieEvent): self
    {
        $this->categorie_event->removeElement($categorieEvent);

        return $this;
    }


    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEvent() === $this) {
                $comment->setEvent(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        $this-> name;

        return $this;
    }

    

    public function getArtist(): ?User
    {
        return $this->artist;
    }

    public function setArtist(?User $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Set the value of images
     *
     * @return  self
     */ 
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCpLocation(): ?string
    {
        return $this->cp_location;
    }

    public function setCpLocation(string $cp_location): self
    {
        $this->cp_location = $cp_location;

        return $this;
    }

    public function getCityLocation(): ?string
    {
        return $this->city_location;
    }

    public function setCityLocation(string $city_location): self
    {
        $this->city_location = $city_location;

        return $this;
    }

    public function getNameLocation(): ?string
    {
        return $this->name_location;
    }

    public function setNameLocation(string $name_location): self
    {
        $this->name_location = $name_location;

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addReserve($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeReserve($this);
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
            $this->reservations[] = $reservation;
            $reservation->setEvent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEvent() === $this) {
                $reservation->setEvent(null);
            }
        }

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(int $nb_place): self
    {
        $this->nb_place = $nb_place;

        return $this;
    }

    

}
