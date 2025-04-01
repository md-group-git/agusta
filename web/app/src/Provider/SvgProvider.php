<?php

declare(strict_types=1);

namespace App\Provider;

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\MediaBundle\Provider\Metadata;

class SvgProvider extends FileProvider
{
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
            ['class' => 'fa fa-file-image-o']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        $path = $this->getReferenceImage($media);

        return $this->getCdn()->getPath($path, $media->getCdnIsFlushable());
    }
}
