<?php

declare(strict_types=1);

namespace App\Provider;

use FFMpeg\FFMpeg;
use Gaufrette\File;
use Gaufrette\Filesystem;
use Psr\Log\LoggerInterface;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Filesystem\Local;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Resizer\ResizerInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;

abstract class MpegProvider extends FileProvider
{
    /**
     * @var FFMpeg
     */
    protected $mpeg;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * MpegProvider constructor.
     *
     * @param $name
     * @param Filesystem                    $filesystem
     * @param CDNInterface                  $cdn
     * @param GeneratorInterface            $pathGenerator
     * @param ThumbnailInterface            $thumbnail
     * @param array                         $allowedExtensions
     * @param array                         $allowedMimeTypes
     * @param ResizerInterface              $resizer
     * @param LoggerInterface               $logger
     * @param MetadataBuilderInterface|null $metadata
     */
    public function __construct(
        $name,
        Filesystem $filesystem,
        CDNInterface $cdn,
        GeneratorInterface $pathGenerator,
        ThumbnailInterface $thumbnail,
        array $allowedExtensions,
        array $allowedMimeTypes,
        ResizerInterface $resizer,
        LoggerInterface $logger,
        MetadataBuilderInterface $metadata = null
    ) {
        parent::__construct(
            $name,
            $filesystem,
            $cdn,
            $pathGenerator,
            $thumbnail,
            $allowedExtensions,
            $allowedMimeTypes,
            $metadata
        );

        $this->mpeg = FFMpeg::create([
            'ffmpeg.binaries'  => $_ENV['FFMPEG'],
            'ffprobe.binaries' => $_ENV['FFMPEG_FFPROBE'],
            'timeout'          => $_ENV['FFMPEG_TIMEOUT'],
            'ffmpeg.threads'   => $_ENV['FFMPEG_THREADS'],
        ]);

        $this->resizer = $resizer;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if (MediaProviderInterface::FORMAT_REFERENCE === $format) {
            $path = $this->getReferenceImage($media);
        } else {
            $path = $this->generateThumbnailUrl($media, $format);
        }

        return $this->getCdn()->getPath($path, $media->getCdnIsFlushable());
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        if (MediaProviderInterface::FORMAT_REFERENCE === $format) {
            $path = $this->getReferenceImage($media);
        } else {
            $path = $this->generateThumbnailUrl($media, $format);
        }

        return $path;
    }

    /**
     * @param MediaInterface $media
     * @param string         $format
     *
     * @return string
     */
    abstract protected function generateThumbnailUrl(MediaInterface $media, string $format);

    /**
     * @param MediaInterface $media
     * @param File           $source
     * @param string         $ext
     */
    protected function createThumbnails(MediaInterface $media, File $source, string $ext = 'jpg')
    {
        $context = $media->getContext();

        foreach ($this->getFormats() as $format => $settings) {
            $inContext = substr($format, 0, \strlen($context)) === $context;

            if ($inContext || MediaProviderInterface::FORMAT_ADMIN === $format) {
                $thumb = $this->generatePrivateUrl($media, $format);
                $destination = $this->getFilesystem()->get($thumb, true);

                $this->resizer->resize($media, $source, $destination, $ext, $settings);
            }
        }
    }

    /**
     * @param string $extension
     *
     * @return string
     */
    protected function getTempPath(string $extension): string
    {
        $name = sprintf('%s.%s', uniqid('_', true), $extension);

        return sys_get_temp_dir().\DIRECTORY_SEPARATOR.$name;
    }

    /**
     * @param string $path
     *
     * @return File
     */
    protected function getTempFile(string $path): ?File
    {
        $filesystem = new Filesystem(new Local('/'));

        return $filesystem->get($path);
    }
}
