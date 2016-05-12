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
    private $ts;

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
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ts
     *
     * @param int $ts
     * @return self
     */
    public function setTs($ts)
    {
        $this->ts = $ts;
        return $this;
    }

    /**
     * Get ts
     *
     * @return timestamp $ts
     */
    public function getTs()
    {
        return $this->ts;
    }
}
