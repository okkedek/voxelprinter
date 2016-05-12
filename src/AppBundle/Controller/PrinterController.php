<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
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
     * Angular view: the 3d result view
     *
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
     * Returns the current voxel model
     *
     * @Route("/load", name="printer_load")
     */
    public function loadAction()
    {
        $printer = $this->printerRepository->loadPrinter();

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
        $printer = $this->printerRepository->loadPrinter();
        $printer->moveNozzle($x, $y);
        $this->printerRepository->savePrinter($printer);

        return $this->renderResponse($printer);
    }

    /**
     * Handles command
     *
     * @Route("/command", name="printer_command")
     */
    public function commandAction(Request $request)
    {
        $command = $request->get('command');
        $printer = $this->printerRepository->loadPrinter();

        switch ($command) {
            case 'nextLayer':
                $printer->nextLayer(); break;
            case 'toggleNozzle':
                $printer->toggleNozzle(); break;
            case 'clear':
                $printer->clear(); break;
            default:
                throw new \Exception("Unkown command: " . $command);
        }

        $this->printerRepository->savePrinter($printer);

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

        $printer = $this->printerRepository->loadPrinter();

        $response = $this->render('@App/printer/voxelmodel.stl.twig', [
            'voxels' => $printer->getVoxelModel()->getVoxels()
        ]);

        return $response;
    }

    /**
     * Adds a snapshot image to the creation gallery
     *
     * @Route("/snapshot", name="printer_snapshot")
     */
    public function addSnapshotAction(Request $request)
    {
        $imgBase64 = $request->get('img');

        $image = new Image();
        $image->setTs(time());
        $image->setData($imgBase64);

        $repository = $this->get('repository.images');
        $repository->add($image);

        return $this->snapshotsAction();
    }

    /**
     * Returns all snapshots in the creation gallery
     *
     * @Route("/snapshots", name="printer_snapshots")
     */
    public function snapshotsAction()
    {
        $repository = $this->get('repository.images');
        $images = $repository->findBy([],['ts' => -1],6);

        return new JsonResponse([
                'images' => $images
            ]
        );
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