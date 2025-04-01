<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\GalleryHasMedia;
use App\Entity\Media;
use SilasJoisten\Sonata\MultiUploadBundle\Controller\MultiUploadController;
use Sonata\MediaBundle\Admin\GalleryAdmin;
use Sonata\MediaBundle\Entity\GalleryManager;
use Sonata\MediaBundle\Entity\MediaManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class MediaAdminController extends MultiUploadController
{
    /**
     * MediaAdminController constructor.
     *
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager)
    {
        parent::__construct($mediaManager);
    }

    /**
     * @param Request        $request
     * @param MediaManager   $mediaManager
     * @param GalleryManager $galleryManager
     * @param GalleryAdmin   $galleryAdmin
     *
     * @return RedirectResponse
     */
    public function createGalleryAction(Request $request, MediaManager $mediaManager, GalleryManager $galleryManager, GalleryAdmin $galleryAdmin): RedirectResponse
    {
        $idx = $request->query->get('idx');
        $idx = json_decode($idx);

        $gallery = $galleryManager->create();
        $gallery->setName('Auto Created Gallery');
        $gallery->setEnabled(true);
        $gallery->setContext('default');

        $list = [];
        foreach ($idx as $id) {
            $list[] = $mediaManager->find($id);
        }

        usort($list, function ($item1, $item2) {
            /* @var Media $item1 */
            /* @var Media $item2 */
            return $item1->getName() <=> $item2->getName();
        });

        $position = 1;
        foreach ($list as $media) {
            /** @var Media $media */
            $galleryHasMedia = new GalleryHasMedia();
            $galleryHasMedia->setGallery($gallery);
            $galleryHasMedia->setMedia($media);
            $galleryHasMedia->setPosition($position++);
            $gallery->addGalleryHasMedia($galleryHasMedia);
        }

        $galleryManager->save($gallery);

        return $this->redirect($galleryAdmin->generateObjectUrl('edit', $gallery));
    }
}
