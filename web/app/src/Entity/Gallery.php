<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Entity\BaseGallery;

/**
 * @ORM\Entity(repositoryClass=GalleryRepository::class)
 */
class Gallery extends BaseGallery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
