<?php


namespace AppBundle\Entity;


class VoxelTest extends \PHPUnit_Framework_TestCase
{

    public function testToArray()
    {
        $voxel = new Voxel(1, 2, 3);
        $array = $voxel->toArray();

        $this->assertEquals([1, 2, 3], $array);

        $voxel = new Voxel(0, 0, 0);
        $array = $voxel->toArray();

        $this->assertEquals([0, 0, 0], $array);
    }
}
