<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GalleryHasMediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Entity\BaseGalleryHasMedia;

/**
 * @ORM\Entity(repositoryClass=GalleryHasMediaRepository::class)
 */
class GalleryHasMedia extends BaseGalleryHasMedia
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
