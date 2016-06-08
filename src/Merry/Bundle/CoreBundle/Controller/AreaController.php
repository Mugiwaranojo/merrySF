<?php

namespace Merry\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Merry\Bundle\CoreBundle\Constants;

class AreaController extends Controller{
    
    public function getAllAction()
    {
        $areaService= $this->get(Constants\ServicesNames::AreaService);
        return $areaService->getAll();
    }
}
