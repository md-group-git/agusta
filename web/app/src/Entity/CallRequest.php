<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CallRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CallRequestRepository::class)
 */
class CallRequest extends ClientRequest
{
    /**
     * @var string
     *
     * @Assert\Length(max = 500)
     *
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $message;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
