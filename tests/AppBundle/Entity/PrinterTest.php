<?php

namespace AppBundle\Entity;

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

    public function testPrinterShouldAddVoxels()
    {
        $printer = $this->printer;
        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(0,2);
        $printer->moveNozzle(1,2);

        $model = $printer->getVoxelModel();
        $this->assertEquals(4 , $model->getVoxelCount());
    }

    public function testPrinterShouldNotAddAtTheSamePositionTwice()
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

    public function testPrinterShouldBeAbleToAddVoxelsToMultipleLayers()
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

    public function testPrinterShouldRespectTheNozzleState()
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

    public function testPrinterShouldNotPrintOutsideItsConfiguredSize() {
        $printer = new Printer($size = 5);

        $printer->moveNozzle(0,0);
        $printer->moveNozzle(0,1);
        $printer->moveNozzle(5,2);
        $printer->moveNozzle(1,5);
        $printer->moveNozzle(-2,2);
        $printer->moveNozzle(1,-2);
        $printer->moveNozzle(10,12);

        $model = $printer->getVoxelModel();
        $this->assertEquals(2 , $model->getVoxelCount());

        $printer->nextLayer();
        $printer->nextLayer();
        $printer->nextLayer();
        $printer->nextLayer();
        $printer->moveNozzle(0,0);
        $this->assertEquals(3 , $model->getVoxelCount());

        $printer->nextLayer();
        $printer->moveNozzle(0,0);
        $this->assertEquals(3 , $model->getVoxelCount());
    }
}
