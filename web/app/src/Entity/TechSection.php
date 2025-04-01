<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TechSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(
 *     fields={"parent", "name"},
 *     errorPath="name",
 *     message="this_type_already_exists_in_this_section"
 * )
 * @ORM\Entity(repositoryClass=TechSectionRepository::class)
 */
class TechSection
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var self
     *
     * @ORM\ManyToOne(targetEntity=TechSection::class, inversedBy="children")
     */
    private $parent;

    /**
     * @var Collection|self[]
     *
     * @ORM\OneToMany(targetEntity=TechSection::class, mappedBy="parent")
     */
    private $children;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->parent) {
            return $this->parent->getName().', '.$this->getName();
        }

        return (string) $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

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
}
