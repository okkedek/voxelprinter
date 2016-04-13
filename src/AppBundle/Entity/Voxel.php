<?php


namespace AppBundle\Entity;

/**
 * Represents a single voxel in a 3d model (value entity)
 *
 * @package AppBundle\Entity
 */
class Voxel
{
    public $x;
    public $y;
    public $z;

    public function __construct($x, $y, $z)
    {
        $this->x = (int)$x;
        $this->y = (int)$y;
        $this->z = (int)$z;
    }

    public function toArray()
    {
        return [$this->x, $this->y, $this->z];
    }

}