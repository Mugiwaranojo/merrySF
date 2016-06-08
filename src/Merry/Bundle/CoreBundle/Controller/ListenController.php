<?php

namespace Merry\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Merry\Bundle\CoreBundle\Constants;
use Symfony\Component\HttpFoundation\Request;

class ListenController extends Controller{
    
    public function receiveAction(Request $request)
    {
        $listenService = $this->get(Constants\ServicesNames::ListenService);
        $sentences= $request->get("sentences");
        $areaId= $request->get("areaId");
        return $listenService->receive($sentences, $areaId);
    }
}
