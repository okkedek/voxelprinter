<?php


namespace AppBundle\Entity;


class VoxelModelTest extends \PHPUnit_Framework_TestCase
{


    public function testAddDistinctVoxels()
    {
        $voxelModel = new VoxelModel();
        $voxel = new Voxel(1, 2, 3);
        $voxelModel->add($voxel);
        $this->assertEquals(1, $voxelModel->getVoxelCount());

        $voxel = new Voxel(2, 3, 4);
        $voxelModel->add($voxel);
        $this->assertEquals(2, $voxelModel->getVoxelCount());
    }

    public function testAddOverlappingVoxels()
    {
        $voxelModel = new VoxelModel();
        $voxel = new Voxel(1, 2, 3);
        $voxelModel->add($voxel);
        $this->assertEquals(1, $voxelModel->getVoxelCount());

        $voxelModel->add($voxel);
        $this->assertEquals(1, $voxelModel->getVoxelCount());

        $voxelModel->add(new Voxel(1, 2, 3));
        $this->assertEquals(1, $voxelModel->getVoxelCount());
    }

    public function testVoxelReturn()
    {
        $voxelModel = new VoxelModel();
        $voxelModel->add(new Voxel(1, 2, 3));
        $voxelModel->add(new Voxel(2, 3, 4));
        $voxelModel->add(new Voxel(4, 5, 6));
        $voxelModel->add(new Voxel(4, 5, 6));

        $voxels = $voxelModel->getVoxels();

        $this->assertEquals(count($voxels), $voxelModel->getVoxelCount());
        $this->assertEquals(3, count($voxels));
        $this->assertTrue( $voxels[0] instanceof Voxel);
        $this->assertTrue( $voxels[1] instanceof Voxel);
        $this->assertTrue( $voxels[2] instanceof Voxel);

        $this->assertEquals([1,2,3],$voxels[0]->toArray());
        $this->assertEquals([2,3,4],$voxels[1]->toArray());
        $this->assertEquals([4,5,6],$voxels[2]->toArray());
    }


}

