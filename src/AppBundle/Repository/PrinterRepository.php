<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Printer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PrinterRepository
{
    private static $SESSION_KEY = '__VXl__PRN__';

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return Printer
     */
    public function loadPrinter()
    {
        $printer = $this->session->get(self::$SESSION_KEY);

        if ($printer === null) {
            $printer = new Printer(0);
        }

        return $printer;
    }

    /**
     * Save printer state to session
     *
     * @param $printer Printer
     */
    public function savePrinter($printer)
    {
        $this->session->set(self::$SESSION_KEY , $printer);
    }
}