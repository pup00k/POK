<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="L'adresse email est déjà prise")
 * @UniqueEntity(fields={"pseudo"}, message="Le pseudo est déjà utilisé")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registerDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $phoneNumber;

    /**
     * @ORM\ManyToMany(targetEntity=Music::class, inversedBy="users", orphanRemoval=false)
     */
    private $user_u;

    /**
     * @ORM\ManyToMany(targetEntity=Music::class, mappedBy="user_artist")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $music_by_artist;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="users")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $reserve;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $comments;


    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo_background;


    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="artist", orphanRemoval=true)
     */
    private $events;



    public function __construct()
    {
        $this->user_u = new ArrayCollection();
        $this->music_by_artist = new ArrayCollection();
        $this->reserve = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
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

    public function addRoles($role) 
    { 
        $this->roles[] = $role;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }


    public function getIsVerified(): ?bool{
       
        return $this->isVerified;
    }
    
    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getUserU(): Collection
    {
        return $this->user_u;
    }

    public function addUserU(Music $userU): self
    {
        if (!$this->user_u->contains($userU)) {
            $this->user_u[] = $userU;
        }

        return $this;
    }

    public function removeUserU(Music $userU): self
    {
        $this->user_u->removeElement($userU);

        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getMusicByArtist(): Collection
    {
        return $this->music_by_artist;
    }

    public function addMusicByArtist(Music $musicByArtist): self
    {
        if (!$this->music_by_artist->contains($musicByArtist)) {
            $this->music_by_artist[] = $musicByArtist;
            $musicByArtist->addUserArtist($this);
        }

        return $this;
    }

    public function removeMusicByArtist(Music $musicByArtist): self
    {
        if ($this->music_by_artist->removeElement($musicByArtist)) {
            $musicByArtist->removeUserArtist($this);
        }

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

    public function getPhotoBackground(): ?string
    {
        return $this->photo_background;
    }

    public function setPhotoBackground(?string $photo_background): self
    {
        $this->photo_background = $photo_background;

        return $this;
    }


    public function __toString()
    {
       return $this->getEmail();
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setArtist($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getArtist() === $this) {
                $event->setArtist(null);
            }
        }

        return $this;
    }

    
}
