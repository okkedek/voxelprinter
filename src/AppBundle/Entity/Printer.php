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
        $this->layer++;
    }

    public function clear()
    {
        $this->model = new VoxelModel();
    }

    public function toggleNozzle()
    {
        $this->nozzleOpen = !$this->nozzleOpen;
    }


}