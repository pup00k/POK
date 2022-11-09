<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $Name_category;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="categorie_event")
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity=Music::class, inversedBy="categories")
     */
    private $category_music;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->category_music = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategory(): ?string
    {
        return $this->Name_category;
    }

    public function setNameCategory(string $Name_category): self
    {
        $this->Name_category = $Name_category;

        return $this;
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
            $event->addCategorieEvent($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeCategorieEvent($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getCategoryMusic(): Collection
    {
        return $this->category_music;
    }

    public function addCategoryMusic(Music $categoryMusic): self
    {
        if (!$this->category_music->contains($categoryMusic)) {
            $this->category_music[] = $categoryMusic;
        }

        return $this;
    }

    public function removeCategoryMusic(Music $categoryMusic): self
    {
        $this->category_music->removeElement($categoryMusic);

        return $this;
    }

    public function __toString()
    {
        return $this->Name_category;

    }
}
