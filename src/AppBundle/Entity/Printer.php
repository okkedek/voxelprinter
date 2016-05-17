<?php


namespace AppBundle\Entity;

/**
 * A 3D Printer with a nozzle that opens and closes
 * Prints a Voxelmodel
 *
 * @package AppBundle\Entity
 */
class Printer
{
    /**
     * @var VoxelModel Represents the printed model
     */
    protected $model;

    /**
     * @var int maximum width and height
     */
    protected $size;

    /**
     * @var int Current layer being printed
     */
    protected $layer = 0;

    /**
     * @var bool Nozzle state, true if nozzle is open
     */
    protected $nozzleOpen = true;

    public function __construct($size = 10)
    {
        $this->size = $size - 1;
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
        if ($this->nozzleOpen !== true) return;
        if ($x < 0 || $y < 0) return;
        if ($x > $this->size || $y > $this->size) return;


        $this->model->add(new Voxel($x, $this->layer, $y));
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

    /**
     * @return int
     */
    public function getCurrentLayer()
    {
        return $this->layer;
    }

    /**
     * Moves nozzle to next layer
     */
    public function nextLayer()
    {
        if ($this->layer < $this->size) {
            $this->layer++;
        }
    }

    /**
     * Clear current model
     */
    public function clear()
    {
        $this->model = new VoxelModel();
        $this->layer = 0;
    }

    /**
     * Open/close the nozzle
     */
    public function toggleNozzle()
    {
        $this->nozzleOpen = !$this->nozzleOpen;
    }

    public function getNozzleState()
    {
        return $this->nozzleOpen;
    }
}