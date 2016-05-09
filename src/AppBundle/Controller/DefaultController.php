<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 *
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_index")
     *
     */
    public function indexAction()
    {
        return $this->render('@App/default/index.html.twig');
    }
}
