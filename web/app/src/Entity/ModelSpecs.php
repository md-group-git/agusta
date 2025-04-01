<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ModelSpecs
{
    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $specs = [];

    public function getModelSpecs(): array
    {
        return $this->specs;
    }

    public function setModelSpecs(array $specs): self
    {
        $this->specs = $specs;

        return $this;
    }

    public function getCylinders(): ?string
    {
        return $this->getSpecs('cylinders');
    }

    public function setCylinders(string $value): self
    {
        return $this->setSpecs('cylinders', $value);
    }

    public function getVolume(): ?string
    {
        return $this->getSpecs('volume');
    }

    public function setVolume(string $value): self
    {
        return $this->setSpecs('volume', $value);
    }

    public function getSpeed(): ?string
    {
        return $this->getSpecs('speed');
    }

    public function setSpeed(string $value): self
    {
        return $this->setSpecs('speed', $value);
    }

    public function getWeight(): ?string
    {
        return $this->getSpecs('weight');
    }

    public function setWeight(string $value): self
    {
        return $this->setSpecs('weight', $value);
    }

    public function getPower(): ?string
    {
        return $this->getSpecs('power');
    }

    public function setPower(string $value): self
    {
        return $this->setSpecs('power', $value);
    }

    public function getPowerRpm(): ?string
    {
        return $this->getSpecs('powerRpm');
    }

    public function setPowerRpm(string $value): self
    {
        return $this->setSpecs('powerRpm', $value);
    }

    public function getCompression(): ?string
    {
        return $this->getSpecs('compression');
    }

    public function setCompression(?string $value): self
    {
        return $this->setSpecs('compression', $value);
    }

    public function getTorque(): ?string
    {
        return $this->getSpecs('torque');
    }

    public function setTorque(string $value): self
    {
        return $this->setSpecs('torque', $value);
    }

    public function getTorqueRpm(): ?string
    {
        return $this->getSpecs('torqueRpm');
    }

    public function setTorqueRpm(string $value): self
    {
        return $this->setSpecs('torqueRpm', $value);
    }

    private function getSpecs(string $property): ?string
    {
        return isset($this->specs[$property]) ? $this->specs[$property] : null;
    }

    private function setSpecs(string $property, string $value): self
    {
        $this->specs[$property] = $value;

        return $this;
    }
}
