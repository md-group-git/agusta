<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRequestRepository::class)
 */
class OrderRequest extends ClientRequest
{
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
}
