<?php


namespace AppBundle\Entity;

/**
 * A 3D model consisting of Voxels
 */
class VoxelModel
{
    protected $voxels = [];

    public function getVoxelCount()
    {
        return count($this->voxels);
    }

    /**
     * @param Voxel $voxel
     */
    public function add(Voxel $voxel)
    {
        $index = join( "," , $voxel->toArray());
        $this->voxels[$index] = $voxel;
    }

    /**
     * @return Voxel[]
     */
    public function getVoxels()
    {
        return array_values($this->voxels);
    }
}