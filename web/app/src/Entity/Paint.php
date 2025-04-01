<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PaintRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PaintRepository::class)
 */
class Paint
{
    use Position;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity=Model::class, inversedBy="paints")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private $model;

    /**
     * @var PaintColor
     *
     * @ORM\ManyToOne(targetEntity=PaintColor::class)
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $paintColor;

    /**
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity=Gallery::class)
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $gallery;

    /**
     * @var string
     *
     * @Assert\Length(max = 255)
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPaintColor(): ?PaintColor
    {
        return $this->paintColor;
    }

    public function setPaintColor(?PaintColor $paintColor): self
    {
        $this->paintColor = $paintColor;

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
