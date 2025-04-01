<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\LocationEnum;
use App\Repository\ModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ModelRepository::class)
 */
class Model implements SluggableInterface
{
    use SluggableTrait;
    use SortablePosition;
    use ModelMedia;
    use ModelSpecs;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Length(max = 255)
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slogan;

    /**
     * @var Lineup
     *
     * @ORM\ManyToOne(targetEntity=Lineup::class, inversedBy="models")
     * @ORM\JoinColumn(name="lineup_id", referencedColumnName="id")
     */
    private $lineup;

    /**
     * @var Collection|Paint[]
     *
     * @ORM\OneToMany(targetEntity=Paint::class, mappedBy="model", orphanRemoval=true, cascade={"all"})
     * @ORM\JoinColumn(name="paint_id", referencedColumnName="id")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $paints;

    /**
     * @var Collection|Highlight[]
     *
     * @ORM\OneToMany(targetEntity=Highlight::class, mappedBy="model", orphanRemoval=true, cascade={"all"})
     * @ORM\JoinColumn(name="highlight_id", referencedColumnName="id")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $highlights;

    /**
     * @var Collection|TechSpec[]
     *
     * @ORM\OneToMany(targetEntity=TechSpec::class, mappedBy="model", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $techSpecs;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price = 0.0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $stockStatus;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $special = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $ride = false;

    public function __construct()
    {
        $this->paints = new ArrayCollection();
        $this->highlights = new ArrayCollection();
        $this->techSpecs = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->lineup) {
            return $this->lineup->getName().' '.$this->getName();
        }

        return (string) $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getSluggableFields(): array
    {
        return ['name'];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getLineup(): ?Lineup
    {
        return $this->lineup;
    }

    public function setLineup(?Lineup $lineup): self
    {
        $this->lineup = $lineup;

        return $this;
    }

    /**
     * @return Collection|Paint[]
     */
    public function getPaints(): Collection
    {
        return $this->paints;
    }

    public function addPaint(Paint $paint): self
    {
        if (!$this->paints->contains($paint)) {
            $this->paints[] = $paint;
            $paint->setModel($this);
        }

        return $this;
    }

    public function removePaint(Paint $paint): self
    {
        if ($this->paints->contains($paint)) {
            $this->paints->removeElement($paint);
            if ($paint->getModel() === $this) {
                $paint->setModel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Highlight[]
     */
    public function getHighlights(): Collection
    {
        return $this->highlights;
    }

    /**
     * @return Collection|Highlight[]
     */
    public function getTopHighlights(): Collection
    {
        return $this->highlights->filter(function (Highlight $highlight) {
            if (LocationEnum::TOP === $highlight->getLocation()) {
                return $highlight;
            }

            return null;
        });
    }

    /**
     * @return Collection|Highlight[]
     */
    public function getBottomHighlights(): Collection
    {
        return $this->highlights->filter(function (Highlight $highlight) {
            if (LocationEnum::BOTTOM === $highlight->getLocation()) {
                return $highlight;
            }

            return null;
        });
    }

    public function addHighlight(Highlight $highlight): self
    {
        if (!$this->highlights->contains($highlight)) {
            $this->highlights[] = $highlight;
            $highlight->setModel($this);
        }

        return $this;
    }

    public function removeHighlight(Highlight $highlight): self
    {
        if ($this->highlights->contains($highlight)) {
            $this->highlights->removeElement($highlight);
            if ($highlight->getModel() === $this) {
                $highlight->setModel(null);
            }
        }

        return $this;
    }

    public function getPrice(): float
    {
        return (float) $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStockStatus(): ?string
    {
        return $this->stockStatus;
    }

    public function setStockStatus(string $stockStatus): self
    {
        $this->stockStatus = $stockStatus;

        return $this;
    }

    public function getSpecial(): bool
    {
        return $this->special;
    }

    public function setSpecial(bool $special): self
    {
        $this->special = $special;

        return $this;
    }

    public function getRide(): ?bool
    {
        return $this->ride;
    }

    public function setRide(bool $ride): self
    {
        $this->ride = $ride;

        return $this;
    }

    /**
     * @return Collection|TechSpec[]
     */
    public function getTechSpecs(): Collection
    {
        return $this->techSpecs;
    }

    public function addTechSpec(TechSpec $techSpec): self
    {
        if (!$this->techSpecs->contains($techSpec)) {
            $this->techSpecs[] = $techSpec;
            $techSpec->setModel($this);
        }

        return $this;
    }

    public function removeTechSpec(TechSpec $techSpec): self
    {
        if ($this->techSpecs->contains($techSpec)) {
            $this->techSpecs->removeElement($techSpec);
            // set the owning side to null (unless already changed)
            if ($techSpec->getModel() === $this) {
                $techSpec->setModel(null);
            }
        }

        return $this;
    }

    public function getTechSpecsTree(): array
    {
        $tree = [];

        $iterator = $this->techSpecs->getIterator();
        $iterator->uasort(function (TechSpec $a, TechSpec $b) {
            return $a->getPosition() <=> $b->getPosition();
        });

        $techSpecs = iterator_to_array($iterator);

        foreach ($techSpecs as $techSpec) {
            $type = $techSpec->getTechSection();
            $section = $type->getParent();

            $tree[$section->getId()]['name'] = $section->getName();
            $tree[$section->getId()]['types'][$type->getId()]['name'] = $type->getName();
            $tree[$section->getId()]['types'][$type->getId()]['value'] = $techSpec->getText();
        }

        return $tree;
    }
}
