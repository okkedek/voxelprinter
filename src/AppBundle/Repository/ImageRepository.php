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

    public function add(Image $image) {
        $this->dm->persist($image);
        $this->dm->flush();
    }

}