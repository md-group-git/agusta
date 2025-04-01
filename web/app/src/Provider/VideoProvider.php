<?php

declare(strict_types=1);

namespace App\Provider;

use Exception;
use FFMpeg\Coordinate\TimeCode;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\Metadata;

class VideoProvider extends MpegProvider
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
            ['class' => 'fa fa-file-video-o']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function doTransform(MediaInterface $media)
    {
        parent::doTransform($media);

        $content = $media->getBinaryContent();
        if ($content) {
            $probe = $this->mpeg->getFFProbe();

            $stream = $probe->streams($content->getRealPath())->videos()->first();
            if ($stream) {
                $media->setWidth($stream->get('width'));
                $media->setHeight($stream->get('height'));
                $media->setLength($stream->get('duration'));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generateThumbnails(MediaInterface $media)
    {
        try {
            $video = $this->mpeg->open($media->getBinaryContent()->getRealPath());

            $duration = $media->getLength();
            $timeCode = TimeCode::fromSeconds((int) ($duration / 2));
            $frame = $video->frame($timeCode);

            $path = $this->getTempPath('jpg');
            $frame->save($path);

            $file = $this->getTempFile($path);
            $this->createThumbnails($media, $file);
            $file->delete();
        } catch (Exception $exception) {
            $this->logger->error(sprintf('Video provider error: %s', $exception->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function generateThumbnailUrl(MediaInterface $media, string $format)
    {
        return sprintf('%s/thumb_%s_%s.%s', $this->generatePath($media), $media->getId(), $format, 'jpg');
    }
}
