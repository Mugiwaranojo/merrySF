<?php

namespace Merry\Bundle\CoreBundle\Services;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Merry\Bundle\CoreBundle\Interfaces\IRequestService;
use Merry\Bundle\CoreBundle\Constants;

class ZWaveRequestService implements IRequestService{
    
    private $logger;
    private $configZwave;
    private $httpRequest;
    
    public function __construct(LoggerInterface $logger, $container)
    {
       $this->logger = $logger;
       $this->httpRequest = $container->get(Constants\ServicesNames::HttpBasicRequestService);
       $this->configZwave = $container->getParameter(Constants\Devices::CONFIG_ZWAVE);
    }
    
    
    public function sendQuery($params){
        $result = $this->query($this->configZwave["host"], $this->configZwave["port"], $params);
        if($result)
        {
            return $result;
        }
        return null;
    }
    
    public function query($cfgHost, $cfgPort, $data = null) 
    {
        $params="";
        if($data){
            foreach ($data as $value){
                $params.="/".$value;
            }
        }
        
        $dataHttp= array('path'=>"/api/HomeAutomation.ZWave".$params,
                         'method'=>'GET',
                        'username'=>$this->configZwave["username"],
                        'password'=>$this->configZwave["password"]);
        return $this->httpRequest->query($cfgHost, $cfgPort, $dataHttp);
    }
    
    public function getModulesList(){
        $dataHttp= array('path'=>"/api/HomeAutomation.HomeGenie/Config/Modules.List",
                    'method'=>'GET',
                    'username'=>$this->configZwave["username"],
                    'password'=>$this->configZwave["password"]);
        $results= $this->httpRequest->query($this->configZwave["host"], $this->configZwave["port"], $dataHttp);
        $datas= json_decode($results);
        $modules= array();
        foreach ($datas as $device){
            if($device->Domain=="HomeAutomation.ZWave"){
                $modules[]= $device;
            }
        }
        return $modules;
    }
    
}
