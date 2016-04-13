<?php

namespace AppBundle\Controller;

use AppBundle\Entity\VoxelModel;
use AppBundle\Repository\PrinterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 *
 * @Route(service="app.default_controller")
 */
class DefaultController extends Controller
{

    /** @var PrinterRepository */
    private $printerRepository;

    public function __construct(PrinterRepository $printerRepository)
    {
        $this->printerRepository = $printerRepository;
    }

    /**
     * @Route("/", name="printer_index")
     */
    public function indexAction(Request $request)
    {
        $printer = $this->printerRepository->loadPrinter();
        $printer->moveNozzle(1, 2);
        $this->printerRepository->savePrinter($printer);

        return $this->render('@App/default/index.html.twig');
    }

    /**
     * @Route("/grid", name="printer_grid")
     */
    public function gridAction(Request $request)
    {
        $viewModel = [
            'width' => 10,
            'height' => 10,
        ];
        return $this->render('@App/default/grid.html.twig', $viewModel);
    }

    /**
     * @Route("/load", name="printer_load")
     */
    public function loadAction(Request $request)
    {
        $printer = $this->printerRepository->loadPrinter();

        return $this->renderVoxelModel($printer->getVoxelModel());
    }

    /**
     * @Route("/move", name="printer_move")
     */
    public function moveAction(Request $request)
    {
        list($x, $y) = $request->get('coord');
        $printer = $this->printerRepository->loadPrinter();
        $printer->moveNozzle($x, $y);
        $this->printerRepository->savePrinter($printer);

        return $this->renderVoxelModel($printer->getVoxelModel());
    }

    /**
     * @Route("/clear", name="printer_clear")
     */
    public function clearAction(Request $request)
    {
        $printer = $this->printerRepository->loadPrinter();
        $printer->clear();
        $this->printerRepository->savePrinter($printer);

        return $this->renderVoxelModel($printer->getVoxelModel());
    }

    /**
     * Returns a VoxelModel JSON response
     *
     * @param VoxelModel $voxelModel
     * @return JsonResponse
     * @throws \Exception
     */
    protected function renderVoxelModel(VoxelModel $voxelModel)
    {
        $response = new JsonResponse();
        $response->setData(array(
            'voxel_count' => $voxelModel->getVoxelCount(),
            'voxels' => $voxelModel->getVoxels(),
        ));

        return $response;
    }
}
