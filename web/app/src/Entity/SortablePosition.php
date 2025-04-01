<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait SortablePosition
{
    /**
     * @var int
     *
     * @Gedmo\SortablePosition()
     * @ORM\Column(name="position", type="integer")
     */
    private $position = 0;

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
