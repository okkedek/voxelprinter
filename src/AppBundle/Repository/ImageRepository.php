<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Image;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * ImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageRepository extends DocumentRepository
{

    /**
     * Adds an image to the gallery
     *
     * @param Image $image
     */
    public function add(Image $image)
    {
        $documentManager = $this->getDocumentManager();
        $documentManager->persist($image);
        $documentManager->flush();
    }

}