<?php

declare(strict_types=1);

namespace App\Provider;

use Gaufrette\Filesystem;
use Imagine\Image\ImagineInterface;
use SilasJoisten\Sonata\MultiUploadBundle\Traits\MultiUploadTrait;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Provider\ImageProvider;
use Sonata\MediaBundle\Provider\Metadata;
use Sonata\MediaBundle\Resizer\ResizerInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;

class MultiuploadImageProvider extends ImageProvider
{
    use MultiUploadTrait;

    /**
     * MultiuploadImageProvider class.
     *
     * @param string                   $name
     * @param Filesystem               $filesystem
     * @param CDNInterface             $cdn
     * @param GeneratorInterface       $pathGenerator
     * @param ThumbnailInterface       $thumbnail
     * @param array                    $allowedExtensions
     * @param array                    $allowedMimeTypes
     * @param ImagineInterface         $adapter
     * @param ResizerInterface         $resizer
     * @param MetadataBuilderInterface $metadata
     */
    public function __construct(
        $name,
        Filesystem $filesystem,
        CDNInterface $cdn,
        GeneratorInterface $pathGenerator,
        ThumbnailInterface $thumbnail,
        array $allowedExtensions,
        array $allowedMimeTypes,
        ImagineInterface $adapter,
        ResizerInterface $resizer,
        ?MetadataBuilderInterface $metadata = null
    ) {
        parent::__construct(
            $name,
            $filesystem,
            $cdn,
            $pathGenerator,
            $thumbnail,
            $allowedExtensions,
            $allowedMimeTypes,
            $adapter,
            $metadata
        );

        $this->resizer = $resizer;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderMetadata()
    {
        return new Metadata(
            $this->getName(),
            $this->getName().'.description',
            null,
            'SonataMediaBundle',
            ['class' => 'fa fa-clone']
        );
    }
}
