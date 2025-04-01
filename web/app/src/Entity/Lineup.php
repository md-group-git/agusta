<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LineupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity("name")
 * @ORM\Entity(repositoryClass=LineupRepository::class)
 */
class Lineup implements SluggableInterface
{
    use SluggableTrait;
    use SortablePosition;

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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var Collection|Model[]
     *
     * @ORM\OneToMany(targetEntity=Model::class, mappedBy="lineup", cascade={"all"})
     */
    private $models;

    public function __construct()
    {
        $this->models = new ArrayCollection();
    }

    public function __toString()
    {
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

    /**
     * @return Collection|Model[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    /**
     * @throws Exception
     *
     * @return Collection|Model[]
     */
    public function getModelsSorted(): Collection
    {
        $iterator = $this->models->getIterator();
        $iterator->uasort(function (Model $a, Model $b) {
            return $a->getPosition() <=> $b->getPosition();
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }

    public function addModel(Model $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->setLineup($this);
        }

        return $this;
    }

    public function removeModel(Model $model): self
    {
        if ($this->models->contains($model)) {
            $this->models->removeElement($model);
            if ($model->getLineup() === $this) {
                $model->setLineup(null);
            }
        }

        return $this;
    }
}
