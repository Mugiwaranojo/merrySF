<?php

namespace Merry\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IhmController extends Controller
{
    public function indexAction()
    {
        return $this->render('MerryFrontBundle:Default:ihm.html.twig');
    }
}
