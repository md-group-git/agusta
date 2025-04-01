<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TechSpecRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TechSpecRepository::class)
 */
class TechSpec
{
    use Position;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity=Model::class, inversedBy="techSpecs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $model;

    /**
     * @var TechSection
     *
     * @ORM\ManyToOne(targetEntity=TechSection::class)
     */
    private $techSection;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max = 1000)
     *
     * @ORM\Column(type="text")
     */
    private $text;

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

    public function getTechSection(): ?TechSection
    {
        return $this->techSection;
    }

    public function setTechSection(?TechSection $techSection): self
    {
        $this->techSection = $techSection;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
