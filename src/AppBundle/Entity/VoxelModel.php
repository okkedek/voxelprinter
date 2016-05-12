<?php


namespace AppBundle\Entity;

/**
 * Represents a 3D model consisting of Voxels
 */

class VoxelModel
{
    protected $voxels = [];

    public function getVoxelCount()
    {
        return count($this->voxels);
    }

    public function add(Voxel $voxel)
    {
        $index = join( "," , $voxel->toArray());
        $this->voxels[$index] = $voxel;
    }

    public function getVoxels()
    {
        return array_values($this->voxels);
    }
}