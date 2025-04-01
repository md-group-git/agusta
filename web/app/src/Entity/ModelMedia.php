<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ModelMedia
{
    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity=Media::class, cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $logo;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity=Media::class, cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $image;

    /**
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity=Gallery::class, cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $header;

    /**
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity=Gallery::class, cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $gallery;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity=Media::class, cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $sound;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity=Media::class, cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $brochure;

    public function getLogo(): ?Media
    {
        return $this->logo;
    }

    public function setLogo(?Media $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setImage(?Media $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getHeader(): ?Gallery
    {
        return $this->header;
    }

    public function setHeader(?Gallery $header): self
    {
        $this->header = $header;

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

    public function getSound(): ?Media
    {
        return $this->sound;
    }

    public function setSound(?Media $sound): self
    {
        $this->sound = $sound;

        return $this;
    }

    public function getBrochure(): ?Media
    {
        return $this->brochure;
    }

    public function setBrochure(?Media $brochure): self
    {
        $this->brochure = $brochure;

        return $this;
    }
}
