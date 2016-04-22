<?php

namespace AppBundle\Service;

use AppBundle\Entity\Printer;
use PHPUnit_Framework_TestCase;

class PrinterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Printer
     */
    protected $printer;

    public function setUp()
    {
        $this->printer = new Printer();
    }

    public function testSingleLayer()
    {
        $printer = $this->printer;
        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(0,2);
        $printer->moveNozzle(1,2);

        $model = $printer->getVoxelModel();
        $this->assertEquals(4 , $model->getVoxelCount());
    }

    public function testSingleLayerIdenticalPositions()
    {
        $printer = $this->printer;
        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(0,2);
        $printer->moveNozzle(1,2);

        //repeat
        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(0,2);
        $printer->moveNozzle(1,2);

        $model = $printer->getVoxelModel();
        $this->assertEquals(4 , $model->getVoxelCount());
    }

    public function testTwoLayers()
    {
        $printer = $this->printer;
        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(0,2);
        $printer->moveNozzle(1,2);

        $printer->nextLayer();

        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(0,2);
        $printer->moveNozzle(1,2);

        $model = $printer->getVoxelModel();
        $this->assertEquals(8 , $model->getVoxelCount());
    }

    public function testNozzle()
    {
        $printer = $this->printer;

        $printer->setNozzleOpen(false);
        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(0,2);
        $printer->moveNozzle(1,2);

        $this->assertEquals(0 , $printer->getVoxelModel()->getVoxelCount());

        $printer->setNozzleOpen(true);
        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(0,2);
        $printer->moveNozzle(1,2);

        $this->assertEquals(4 , $printer->getVoxelModel()->getVoxelCount());
    }
}
