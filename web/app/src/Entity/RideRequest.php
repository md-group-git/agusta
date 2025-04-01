<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RideRequestRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RideRequestRepository::class)
 */
class RideRequest extends ClientRequest
{
    /**
     * @var DateTimeInterface
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @Assert\Email()
     * @Assert\Length(max = 255)
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity=Model::class)
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $model;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $licensed = false;

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
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

    public function getLicensed(): bool
    {
        return $this->licensed;
    }

    public function setLicensed(bool $licensed): self
    {
        $this->licensed = $licensed;

        return $this;
    }
}
