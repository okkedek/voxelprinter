<?php

namespace AppBundle\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * A snapshot image
 *
 * @MongoDB\Document(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Timestamp
     */
    private $timestamp;

    /**
     * @MongoDB\String
     */
    public $data;

    /**
     * Set data
     *
     * @param string $data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get data
     *
     * @return string $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ts
     *
     * @param int $timestamp
     * @return self
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Get ts
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
