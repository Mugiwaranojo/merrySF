<?php

namespace Merry\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Merry\Bundle\CoreBundle\Constants;

class DeviceController extends Controller
{
    public function getAllAction()
    {
        $deviceService= $this->get(Constants\ServicesNames::DeviceService);
        return $deviceService->getAll();
    }
    
    public function getByIdentifierAction($deviceIdentifier)
    {
        $deviceService= $this->get(Constants\ServicesNames::DeviceService);
        return $deviceService->getByIdentifier($deviceIdentifier);
    }
    
    public function commandAction($deviceIdentifier, $command, $options=null)
    {
        $deviceService= $this->get(Constants\ServicesNames::DeviceService);
        if(!is_array($options)){
            $options= array(urlencode($options));
        }
        //var_dump($options);return;
        return $deviceService->sendCommand($deviceIdentifier, $command, $options);
    }
}
