<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Repository\ImageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/snapshot", service="controller.snapshot")
 */
class SnapshotController extends Controller
{
    /** @var ImageRepository */
    private $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * Adds a snapshot image to the creation gallery
     *
     * @Route("/add", name="snapshot_add")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $imgBase64 = $request->get('img');

        $image = new Image();
        $image->setTs(time());
        $image->setData($imgBase64);

        $this->imageRepository->add($image);

        return $this->loadAction();
    }

    /**
     * Returns all snapshots in the creation gallery
     *
     * @Route("/load", name="snapshot_load")
     */
    public function loadAction()
    {
        $images = $this->imageRepository->findBy([], ['ts' => -1], 6);

        return new JsonResponse([
                'images' => $images
            ]
        );
    }
}