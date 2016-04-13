<?php


namespace AppBundle\Entity;


class Printer
{

    /**
     * Number of millisecond between voxels printed
     */
    protected $print_speed_micro;

    /**
     * @var VoxelModel
     */
    protected $model;

    /**
     * @var int current layer being printed
     */
    protected $layer = 0;

    /**
     * @var bool true if nozzle is open
     */
    protected $nozzleOpen = true;

    /**
     * @var int timestamp of last voxel printed
     */
    protected $lastVoxelMicroTime;


    public function __construct($print_speed_milli = 1000 )
    {
        $this->model = new VoxelModel();
        $this->print_speed_micro = (float) $print_speed_milli / 1000;
    }

    /**
     * Move nozzle to indicated position and print a voxel if appropriate
     *
     * @param $x
     * @param $y
     */
    public function moveNozzle($x, $y)
    {
        $nowMicro = microtime(true);
        if ($this->nozzleOpen === true && $nowMicro - $this->lastVoxelMicroTime >= $this->print_speed_micro) {
            $this->model->add( new Voxel($x, $y, $this->layer));
            $this->lastVoxelMicroTime = $nowMicro;
        }
    }

    /**
     * Open or close the nozzle
     *
     * @param bool $nozzleOpen
     */
    public function setNozzleOpen($nozzleOpen = true)
    {
        $this->nozzleOpen = $nozzleOpen;
    }

    /**
     * Returns the currently printed model
     *
     * @return VoxelModel
     */
    public function getVoxelModel()
    {
        return $this->model;
    }

    public function nextLayer()
    {
        $this->layer++;
    }

    public function clear()
    {
        $this->model = new VoxelModel();
    }


}