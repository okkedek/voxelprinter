<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Printer;
use AppBundle\Repository\PrinterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * @Route("/printer", service="controller.printer")
 */
class PrinterController extends Controller
{
    /** @var PrinterRepository */
    private $printerRepository;

    public function __construct(PrinterRepository $printerRepository)
    {
        $this->printerRepository = $printerRepository;
    }

    /**
     * Angular view: the printer view with the three grids
     *
     * @Route("/view/grid", name="printer_grid")
     */
    public function gridViewAction()
    {
        $viewModel = [
            'width' => 10,
            'height' => 10,
        ];
        return $this->render('@App/printer/printer.html.twig', $viewModel);
    }

    /**
     * Angular view: the 3d result view
     *
     * @Route("/view/result", name="printer_result")
     */
    public function resultViewAction()
    {
        return $this->render('@App/printer/viewer.html.twig', [
            'model_url' => $this->generateUrl(
                'printer_stlmodel',
                ['sid' => $this->get('session')->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        ]);
    }

    /**
     * Returns the current voxel model
     *
     * @Route("/load", name="printer_load")
     */
    public function loadAction()
    {
        $printer = $this->printerRepository->load();

        return $this->renderResponse($printer);
    }

    /**
     * Moves the nozzle, possible adding a voxel
     *
     * @Route("/move", name="printer_move")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function moveAction(Request $request)
    {
        list($x, $y) = $request->get('coord');
        $printer = $this->printerRepository->load();
        $printer->moveNozzle($x, $y);
        $this->printerRepository->save($printer);

        return $this->renderResponse($printer);
    }

    /**
     * Handles command
     *
     * @Route("/command", name="printer_command")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function commandAction(Request $request)
    {
        $command = $request->get('command');
        $printer = $this->printerRepository->load();

        switch ($command) {
            case 'nextLayer':
                $printer->nextLayer();
                break;
            case 'toggleNozzle':
                $printer->toggleNozzle();
                break;
            case 'clear':
                $printer->clear();
                break;
            default:
                throw new \Exception("Unkown command: " . $command);
        }

        $this->printerRepository->save($printer);

        return $this->renderResponse($printer);
    }

    /**
     * Used by external STL viewer to load the STL view of the current voxelmodel
     *
     * @Route("/model/{sid}.stl", name="printer_stlmodel")
     * @param $sid
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadStlAction($sid)
    {
        // we have to use the session id from the url
        // in order to load the correct model
        $session = $this->container->get('session');
        $session->save(); // closes the current session
        $session->setId(explode(".", $sid)[0]);

        $printer = $this->printerRepository->load();

        $response = $this->render('@App/printer/voxelmodel.stl.twig', [
            'voxels' => $printer->getVoxelModel()->getVoxels()
        ]);

        return $response;
    }

    /**
     * Returns a VoxelModel JSON response
     *
     * @param Printer $printer
     * @return JsonResponse
     */
    protected function renderResponse(Printer $printer)
    {
        return new JsonResponse([
                'currentLayer' => $printer->getCurrentLayer(),
                'voxels' => $printer->getVoxelModel()->getVoxels(),
            ]
        );
    }

}