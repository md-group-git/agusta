<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\RequestStatusEnum;
use App\Repository\ClientRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRequestRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "call"=CallRequest::class,
 *      "order"=OrderRequest::class,
 *      "ride"=RideRequest::class,
 *      "service"=ServiceRequest::class,
 * })
 */
abstract class ClientRequest implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max = 50)
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max = 50)
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max = 20)
     *
     * @ORM\Column(type="string", length=20)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $referer;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status = RequestStatusEnum::NEW;

    public function __toString()
    {
        return sprintf(
            '%s, %s %s',
            $this->getPhone(),
            $this->getFirstName(),
            $this->getLastName()
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(?string $referer): self
    {
        $this->referer = $referer;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
