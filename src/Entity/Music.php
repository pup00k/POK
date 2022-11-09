<?php

namespace App\Entity;

use App\Repository\MusicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MusicRepository::class)
 */
class Music
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
    private $name_music;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="user_u")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="music_by_artist")
     */
    private $user_artist;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="category_music")
     */
    private $categories;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->user_artist = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameMusic(): ?string
    {
        return $this->name_music;
    }

    public function setNameMusic(string $name_music): self
    {
        $this->name_music = $name_music;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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
            $user->addUserU($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeUserU($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserArtist(): Collection
    {
        return $this->user_artist;
    }

    public function addUserArtist(User $userArtist): self
    {
        if (!$this->user_artist->contains($userArtist)) {
            $this->user_artist[] = $userArtist;
        }

        return $this;
    }

    public function removeUserArtist(User $userArtist): self
    {
        $this->user_artist->removeElement($userArtist);

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addCategoryMusic($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeCategoryMusic($this);
        }

        return $this;
    }
}
