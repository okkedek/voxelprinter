<?php


namespace AppBundle\Entity;


class Printer
{
    /**
     * @var VoxelModel Represents the printed model
     */
    protected $model;

    /**
     * @var int Current layer being printed
     */
    protected $layer = 0;

    /**
     * @var bool Nozzle state, true if nozzle is open
     */
    protected $nozzleOpen = true;

    public function __construct( )
    {
        $this->model = new VoxelModel();
    }

    /**
     * Move nozzle to indicated position and print a voxel if appropriate
     *
     * @param $x
     * @param $y
     */
    public function moveNozzle($x, $y)
    {
        if ($this->nozzleOpen === true ) {
            $this->model->add( new Voxel($x,  $this->layer, $y));
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

    public function getCurrentLayer()
    {
        return $this->layer;
    }

    public function nextLayer()
    {
        if ($this->layer < 10) {
            $this->layer++;
        }
    }

    public function clear()
    {
        $this->model = new VoxelModel();
        $this->layer = 0;
    }

    public function toggleNozzle()
    {
        $this->nozzleOpen = !$this->nozzleOpen;
    }


}