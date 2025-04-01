<?php

declare(strict_types=1);

namespace App\Provider;

use Exception;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\Metadata;

class AudioProvider extends MpegProvider
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
            ['class' => 'fa fa-file-audio-o']
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
            // dd($content->guessExtension());

            $probe = $this->mpeg->getFFProbe();

            $stream = $probe->streams($content->getRealPath())->audios()->first();
            if ($stream) {
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
            $audio = $this->mpeg->open($media->getBinaryContent()->getRealPath());

            list($width, $height, $color) = [800, 200, ['#cecece']];
            $waveform = $audio->waveform($width, $height, $color);

            $media->setWidth($width);
            $media->setHeight($height);

            $path = $this->getTempPath('png');
            $waveform->save($path);

            $file = $this->getTempFile($path);
            $this->createThumbnails($media, $file, 'png');
            $file->delete();
        } catch (Exception $exception) {
            $this->logger->error(sprintf('Audio provider error: %s', $exception->getMessage()));
        }
    }

    /**
     * @param MediaInterface $media
     *
     * @return string
     */
    protected function generateReferenceName(MediaInterface $media)
    {
        $extension = $media->getBinaryContent()->guessExtension();
        $extension = ('mpga' === $extension ? 'mp3' : $extension);

        return $this->generateMediaUniqId($media).'.'.$extension;
    }

    /**
     * {@inheritdoc}
     */
    protected function generateThumbnailUrl(MediaInterface $media, string $format)
    {
        return sprintf('%s/thumb_%s_%s.%s', $this->generatePath($media), $media->getId(), $format, 'png');
    }
}
