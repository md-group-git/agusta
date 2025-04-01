<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Gallery;
use App\Entity\GalleryHasMedia;
use App\Entity\Highlight;
use App\Entity\Lineup;
use App\Entity\Media;
use App\Entity\Model;
use App\Entity\Paint;
use App\Entity\PaintColor;
use App\Enum\LocationEnum;
use App\Enum\StockStatusEnum;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Sonata\MediaBundle\Entity\GalleryManager;
use Sonata\MediaBundle\Entity\MediaManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImportContentCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var GalleryManager
     */
    private $galleryManager;

    /**
     * @var ParameterBag
     */
    private $params;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var string
     */
    private $root;

    /**
     * @required
     *
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @required
     *
     * @param MediaManager $mediaManager
     */
    public function setMediaManager(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    /**
     * @required
     *
     * @param GalleryManager $galleryManager
     */
    public function setGalleryManager(GalleryManager $galleryManager)
    {
        $this->galleryManager = $galleryManager;
    }

    /**
     * @required
     *
     * @param ParameterBagInterface $params
     */
    public function setParams(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:content:import')
            ->setDescription('Import content')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->root = $this->params->get('kernel.project_dir');

        try {
            $this->import();
        } catch (Exception $exception) {
            $this->io->error($exception->getMessage());
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function import()
    {
        $list = [
            'brutale1000RR'            => ['lineup' => 'Brutale', 'name' => '1000 RR'],
            'brutale1000SerieOro'      => ['lineup' => 'Brutale', 'name' => '1000 Serie Oro'],
            'brutale800Rosso'          => ['lineup' => 'Brutale', 'name' => '800 Rosso'],
            'brutale800RR'             => ['lineup' => 'Brutale', 'name' => '800 RR'],
            'brutale800RRSCS'          => ['lineup' => 'Brutale', 'name' => '800 RR SCS'],
            'dragster800RC'            => ['lineup' => 'Dragster', 'name' => '800 RC'],
            'dragster800Rosso'         => ['lineup' => 'Dragster', 'name' => '800 Rosso'],
            'dragster800RR'            => ['lineup' => 'Dragster', 'name' => '800 RR'],
            'dragster800RRSCS'         => ['lineup' => 'Dragster', 'name' => '800 RR SCS'],
            'f3675'                    => ['lineup' => 'F3', 'name' => '675'],
            'f3800'                    => ['lineup' => 'F3', 'name' => '800'],
            'f3800RC'                  => ['lineup' => 'F3', 'name' => '800 RC'],
            'rush1000'                 => ['lineup' => 'Rush', 'name' => '1000'],
            'superveloce800'           => ['lineup' => 'Superveloce', 'name' => '800'],
            'superveloce800SerieOro'   => ['lineup' => 'Superveloce', 'name' => '800 Serie Oro'],
            'turismoVeloce800Lusso'    => ['lineup' => 'Turismo Veloce', 'name' => '800 Lusso'],
            'turismoVeloce800LussoScs' => ['lineup' => 'Turismo Veloce', 'name' => '800 Lusso SCS'],
            'turismoVeloce800RCScs'    => ['lineup' => 'Turismo Veloce', 'name' => '800 RC SCS'],
            'turismoVeloce800Rosso'    => ['lineup' => 'Turismo Veloce', 'name' => '800 Rosso'],
        ];

        $file = $this->root.'/assets/import/data.json';
        $data = json_decode(file_get_contents($file), true);

        $lineupPosition = 1;

        foreach ($list as $key => $item) {
            $this->io->text(sprintf('Importing .. %s %s', $item['lineup'], $item['name']));

            $import = $data[$key];
            unset($import['metaContent'], $import['googleOptimizer'], $import['mainSlider']);

            $headerSlider = $import['headerSlider'];
            $rotateSlider = $import['rotateSlider'];

            /** @var Lineup $lineup */
            $lineup = $this->entityManager->getRepository(Lineup::class)->findOneBy([
                'name' => $item['lineup'],
            ]);

            if (!$lineup) {
                $lineup = new Lineup();
                $lineup
                    ->setName($item['lineup'])
                    ->setPosition($lineupPosition++)
                ;

                $this->entityManager->persist($lineup);
            }

            /** @var Model $model */
            $model = $this->entityManager->getRepository(Model::class)->findOneBy([
                'lineup' => $lineup,
                'name'   => $item['name'],
            ]);

            if (!$model) {
                $model = new Model();
                $model
                    ->setName($item['name'])
                    ->setLineup($lineup)
                    ->setStockStatus(StockStatusEnum::IN_STOCK)
                    ->setSpecial(false)
                    ->setRide(false)
                ;

                $this->entityManager->persist($model);

                $this->importPaints($model, $rotateSlider['colorSlides']);
                $this->importHighlights($model, $import['module7'], LocationEnum::BOTTOM);
                $this->importHighlights($model, $import['module6'], LocationEnum::TOP);
            }

            $this->importLogo($model, $headerSlider['logo']);
            $this->importMainImage($model, $headerSlider['logo']);
            $this->importHeaderGallery($model, $headerSlider['slides']);
            $this->importModelGallery($model, $import['videoSlider']);

            $this->io->text(sprintf('Importing .... specs'));

            $price = (float) str_replace([' ', ','], '', $import['price']);
            $model->setPrice($price);

            $description = $rotateSlider['description'];

            $model->setCylinders($description['num']);
            $model->setVolume($description['volume']);
            $model->setSpeed(str_replace('>', '', $description['speed']));
            $model->setWeight($description['weight']);
            $model->setCompression($description['compression']);
            $model->setTorque($description['moment']);
            $model->setTorqueRpm('0');
            $model->setPower($description['power']);
            $model->setPowerRpm('0');
        }

        $this->entityManager->flush();
        $this->io->success('Data successfully imported!');
    }

    /**
     * @param Model  $model
     * @param string $path
     */
    private function importLogo(Model $model, string $path)
    {
        if (!$model->getLogo()) {
            $path = $this->fixContentPath($path);
            $this->io->text(sprintf('Importing .... logo: %s', basename($path)));

            $name = sprintf('%s %s', $model->getLineup()->getName(), $model->getName());

            $media = new Media();
            $media->setBinaryContent($path);
            $media->setContext('logo');
            $media->setProviderName('sonata.media.provider.svg');
            $media->setName($name);
            $this->mediaManager->save($media);

            $model->setLogo($media);
        }
    }

    /**
     * @param Model  $model
     * @param string $path
     */
    private function importMainImage(Model $model, string $path)
    {
        if (!$model->getImage()) {
            $path = str_replace(basename($path), 'main.png', $path);
            $this->io->text(sprintf('Importing .... image: %s', basename($path)));

            $name = sprintf('%s %s', $model->getLineup()->getName(), $model->getName());

            $media = new Media();
            $media->setBinaryContent($this->fixContentPath($path));
            $media->setContext('model');
            $media->setProviderName('sonata.media.provider.image');
            $media->setName($name);
            $this->mediaManager->save($media);

            $model->setImage($media);
        }
    }

    /**
     * @param Model $model
     * @param array $slides
     */
    private function importHeaderGallery(Model $model, array $slides)
    {
        if (!$model->getHeader()) {
            $this->io->text(sprintf('Importing .... gallery (header)'));

            $name = sprintf('%s %s', $model->getLineup()->getName(), $model->getName());

            $gallery = $this->galleryManager->create();
            $gallery->setName($name);
            $gallery->setEnabled(true);
            $gallery->setContext('header_gallery');

            $position = 1;
            foreach ($slides as $slide) {
                $path = $this->fixContentPath($slide);
                $this->io->text(sprintf('Importing ...... media: %s', basename($path)));

                $media = new Media();
                $media->setBinaryContent($path);
                $media->setContext('header_gallery');
                $media->setProviderName('sonata.media.provider.image');
                $media->setEnabled(true);
                $media->setName($name.' '.$position);
                $this->mediaManager->save($media);

                $galleryHasMedia = new GalleryHasMedia();
                $galleryHasMedia->setGallery($gallery);
                $galleryHasMedia->setMedia($media);
                $galleryHasMedia->setPosition($position++);
                $gallery->addGalleryHasMedia($galleryHasMedia);
            }

            $this->galleryManager->save($gallery);
            $model->setHeader($gallery);
        }
    }

    /**
     * @param Model $model
     * @param array $slides
     */
    private function importModelGallery(Model $model, array $slides)
    {
        if (!$model->getGallery()) {
            $this->io->text(sprintf('Importing .... gallery (model)'));

            $name = sprintf('%s %s', $model->getLineup()->getName(), $model->getName());

            $gallery = $this->galleryManager->create();
            $gallery->setName($name);
            $gallery->setEnabled(true);
            $gallery->setContext('bike_gallery');

            $position = 1;
            foreach ($slides['videos'] as $slide) {
                $type = $slide['type'];

                $media = new Media();
                $media->setContext('bike_gallery');
                $media->setEnabled(true);
                $media->setName($name.' '.$position);

                if ('img' === $type) {
                    $path = $this->fixContentPath($slide['src']);
                    $this->io->text(sprintf('Importing ...... media: %s', basename($path)));

                    $media->setBinaryContent($path);
                    $media->setProviderName('sonata.media.provider.image');
                } elseif ('video' === $type) {
                    $this->io->text(sprintf('Importing ...... media: %s', $slide['src']));

                    $media->setBinaryContent($slide['src']);
                    $media->setProviderName('sonata.media.provider.youtube');
                }

                $this->mediaManager->save($media);

                $galleryHasMedia = new GalleryHasMedia();
                $galleryHasMedia->setGallery($gallery);
                $galleryHasMedia->setMedia($media);
                $galleryHasMedia->setPosition($position++);
                $gallery->addGalleryHasMedia($galleryHasMedia);
            }

            $this->galleryManager->save($gallery);
            $model->setGallery($gallery);
        }
    }

    /**
     * @param Model  $model
     * @param array  $slides
     * @param string $location
     */
    private function importHighlights(Model $model, array $slides, string $location)
    {
        $side = LocationEnum::TOP === $location ? 'A' : 'B';
        $name = sprintf('%s %s Detail %s', $model->getLineup()->getName(), $model->getName(), $side);

        $this->io->text(sprintf('Importing .... highlights %s', $side));

        $position = 1;
        foreach ($slides['textSlides'] as $k => $text) {
            $highlight = new Highlight();
            $highlight->setTitle($text['header']);
            $highlight->setDescription($text['text']);
            $highlight->setPosition($position++);
            $highlight->setLocation($location);

            $path = $this->fixContentPath($slides['slides'][$k]);
            $this->io->text(sprintf('Importing ...... media: %s', basename($path)));

            $media = new Media();
            $media->setBinaryContent($path);
            $media->setContext('highlight');
            $media->setProviderName('sonata.media.provider.image');
            $media->setEnabled(true);
            $media->setName($name.''.$position);
            $this->mediaManager->save($media);

            $highlight->setImage($media);

            $model->addHighlight($highlight);
        }
    }

    /**
     * @param Model $model
     * @param array $slides
     *
     * @throws ORMException
     */
    private function importPaints(Model $model, array $slides)
    {
        $paintName = sprintf('%s %s Paint', $model->getLineup()->getName(), $model->getName());
        $position = 1;

        foreach ($slides as $slide) {
            $this->io->text(sprintf('Importing .... paint %s', $position));

            $colorName = str_replace('<br>', "\r\n", $slide['description']);

            /** @var PaintColor $paintColor */
            $paintColor = $this->entityManager->getRepository(PaintColor::class)->findOneBy([
                'name' => $colorName,
            ]);

            if (!$paintColor) {
                $paintColor = new PaintColor();
                $paintColor->setName($colorName);

                $icon = str_replace('/img', $this->root.'/assets/import/color', $slide['icon']);
                $this->io->text(sprintf('Importing ...... media: %s', basename($icon)));

                $media = new Media();
                $media->setBinaryContent($icon);
                $media->setContext('color');
                $media->setProviderName('sonata.media.provider.svg');
                $this->mediaManager->save($media);

                $paintColor->setImage($media);
                $this->entityManager->persist($paintColor);
            }

            $gallery = $this->importPaintGallery($slide['slides'], $paintName.' '.$position);

            $paint = new Paint();
            $paint->setPaintColor($paintColor);
            $paint->setGallery($gallery);
            $paint->setPosition($position++);

            $model->addPaint($paint);
        }
    }

    /**
     * @param array  $slides
     * @param string $name
     *
     * @return Gallery
     */
    private function importPaintGallery(array $slides, string $name): Gallery
    {
        $gallery = $this->galleryManager->create();
        $gallery->setEnabled(true);
        $gallery->setContext('circular');
        $gallery->setName($name);

        $position = 1;
        foreach ($slides as $slide) {
            $path = $this->fixContentPath($slide);
            $this->io->text(sprintf('Importing ...... media: %s', basename($path)));

            $media = new Media();
            $media->setContext('circular');
            $media->setBinaryContent($path);
            $media->setProviderName('sonata.media.provider.image');
            $this->mediaManager->save($media);

            $galleryHasMedia = new GalleryHasMedia();
            $galleryHasMedia->setGallery($gallery);
            $galleryHasMedia->setMedia($media);
            $galleryHasMedia->setPosition($position++);
            $gallery->addGalleryHasMedia($galleryHasMedia);
        }

        $this->galleryManager->save($gallery);

        return $gallery;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function fixContentPath(string $path): string
    {
        return str_replace('/img', $this->root.'/assets/import', $path);
    }
}
