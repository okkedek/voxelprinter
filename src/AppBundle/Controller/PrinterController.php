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
 * Class PrinterController
 *
 * @Route("/printer", service="app.printer_controller")
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
     * @Route("/grid", name="printer_grid")
     */
    public function gridAction()
    {
        $viewModel = [
            'width' => 10,
            'height' => 10,
        ];
        return $this->render('@App/printer/printer.html.twig', $viewModel);
    }

    /**
     * @Route("/load", name="printer_load")
     */
    public function loadAction()
    {
        $printer = $this->printerRepository->loadPrinter();

        return $this->renderResponse($printer);
    }

    /**
     * @Route("/move", name="printer_move")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function moveAction(Request $request)
    {
        list($x, $y) = $request->get('coord');
        $printer = $this->printerRepository->loadPrinter();
        $printer->moveNozzle($x, $y);
        $this->printerRepository->savePrinter($printer);

        return $this->renderResponse($printer);
    }

    /**
     * @Route("/next", name="printer_next_layer")
     */
    public function nextLayerAction()
    {
        $printer = $this->printerRepository->loadPrinter();
        $printer->nextLayer();
        $this->printerRepository->savePrinter($printer);

        return $this->renderResponse($printer);
    }

    /**
     * @Route("/nozzle", name="printer_toggle_nozzle")
     */
    public function toggleNozzleAction()
    {
        $printer = $this->printerRepository->loadPrinter();
        $printer->toggleNozzle();
        $this->printerRepository->savePrinter($printer);

        return $this->renderResponse($printer);
    }

    /**
     * @Route("/clear", name="printer_clear")
     */
    public function clearAction()
    {
        $printer = $this->printerRepository->loadPrinter();
        $printer->clear();
        $this->printerRepository->savePrinter($printer);

        return $this->renderResponse($printer);
    }

    /**
     * Used by external STL viewer to load the STl model
     *
     * @Route("/model/{sid}.stl", name="printer_stlmodel")
     * @param $sid
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resultModelAction($sid)
    {
        // we have to use the session id from the url
        // in order to load the correct model
        $session = $this->container->get('session');
        $session->save(); // closes the current session
        $session->setId(explode(".", $sid)[0]);

        $printer = $this->printerRepository->loadPrinter();

        $response = $this->render('@App/printer/voxelmodel.stl.twig', [
            'voxels' => $printer->getVoxelModel()->getVoxels()
        ]);

        return $response;
    }

    /**
     * @Route("/result", name="printer_result")
     */
    public function resultAction()
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