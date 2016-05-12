<?php


namespace AppBundle\Entity;

/**
 * A voxel in a 3d voxel model
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