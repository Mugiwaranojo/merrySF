<?php
namespace Merry\Bundle\CoreBundle\Services;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Merry\Bundle\CoreBundle\Constants;
use Merry\Bundle\CoreBundle\Entity\Device;
use Merry\Bundle\CoreBundle\Helper\DeviceHelper;
use Merry\Bundle\CoreBundle\AccessObject\Response;

class DeviceService {
    
    private $logger;
    private $container;
    
    public function __construct(LoggerInterface $logger, $container)
    {
       $this->logger = $logger;
       $this->container = $container;
    }
    
    
    public function getAll()
    {
        $response = new Response();
        $devices = $this->getRepository()->getAll();
        $result=array();
        foreach ($devices as $device)
        {
            $result[]= DeviceHelper::mapToRessource($device);
        }
        $response->setSuccess($result);
        return $response;
    }
    
    public function getByIdentifier($identifier)
    {
        $response = new Response();
        $device = $this->getRepository()->getByIdentifier($identifier);
        if(!$device){
            $response->code= Constants\HttpStatusCodes::NOT_FOUND;
            $response->message= sprintf("device %s not found", $identifier);
            $response->updateData();
        }else{
            $response->setSuccess(DeviceHelper::mapToRessource($device));
        }
        return $response;
    }

    public function sendCommand($deviceIdentifier, $command, $options= null)
    {
        $response = new Response();
        $device = $this->getRepository()->getByIdentifier($deviceIdentifier);
        if(!$device){
            $this->logger->error(sprintf("Error : %s is not a valid device", $deviceIdentifier));
            $response->code= Constants\HttpStatusCodes::NOT_FOUND;
            $response->message= sprintf("device %s not found", $deviceIdentifier);
            $response->updateData();
            return $response;
        }
        
        $this->logger->info(sprintf("Send command %s to device %s", $command, $deviceIdentifier));
        $result = "";
        switch ($device->getDeviceType())
        {
            case Constants\Devices::TYPE_PC:
                $result= $this->sendPcCommand($deviceIdentifier, $command, $options);
                break;
            case Constants\Devices::TYPE_MULTIMEDIA:
                $result= $this->sendMultimediaCommand($deviceIdentifier, $command, $options);
                break;
            //device Zwave par default pour les différents type dimmer, sensor, etc...
            default:
                $result= $this->sendZwaveCommand($deviceIdentifier, $command);
                break;
        }
        //Mis a jours du status du device
        $status= $result;
        $this->logger->info($status);
        $device->setStatus($status);
        $device->setUpdated(new \DateTime());
        $this->getRepository()->createOrUpdate($device);
        $response->message= sprintf("%s status is %s", $deviceIdentifier, $status);
        //Changement été des devices enfants
        if(($command==Constants\Devices::COMMAND_ON || $command==Constants\Devices::COMMAND_OFF)&&$device->getDevicesChilds())
        {
            foreach ($device->getDevicesChilds() as $child){
                $this->sendCommand($child->getIdentifier(), $command);
            }
        }
        return $response->setSuccess(DeviceHelper::mapToRessource($device));
    }
    
    public function sendCommandToAll($command){
        $devices = $this->getAll();
        foreach ($devices as $device)
        {
            $this->sendCommand($device->getIdentifier(), $command);
        }
    }
    
    public function allTurnOn(){
       $this->sendCommandToAll(Constants\Devices::COMMAND_ON);
    }
    
    public function allTurnOFF(){
       $this->sendCommandToAll(Constants\Devices::COMMAND_OFF);
    }
    
    private function sendPcCommand($deviceIdentifier, $command, $options=null)
    {
        $pcService = $this->container->get(Constants\ServicesNames::PCService);
        switch($command)
        {  
            case Constants\Devices::COMMAND_ON:
                $pcService->wakeOnLan($deviceIdentifier);
                $result= "Started";
                break;
            case Constants\Devices::COMMAND_OFF:
                $pcService->goToSleep($deviceIdentifier);
                $result= "Sleeping";
                break;
            case Constants\Devices::COMMAND_START:
            case Constants\Devices::COMMAND_CLOSE:
            case Constants\Devices::COMMAND_SAYS:
            case Constants\Devices::COMMAND_SENDKEY:
                $pcService->sendClientRequest($deviceIdentifier, $command, $options);
                $result= 'Running';
                break;
        }
        return $result;
    }
    
    private function sendMultimediaCommand($deviceIdentifier, $command, $options=null)
    {
        $configDevices = $this->container->getParameter(Constants\Devices::CONFIG);
        $config = $configDevices[$deviceIdentifier];
        $result = sprintf("Error while send command %s", $command);
        switch ($config['transport']){
            case Constants\Devices::TRANSPORT_HTTP:
                $httpRequestService = $this->container->get(Constants\ServicesNames::HttpBasicRequestService);
                $data= array('path'=>$config['path'],
                             'method'=>'POST',
                             'username'=>$config['username'],
                             'password'=>$config['password'],
                             'values'=>array('key' => DeviceHelper::getMultimediaCommand($config['command'], $command)));
                $result= $httpRequestService->query($config['host'], $config['port'], $data);
                break;
            case Constants\Devices::TRANSPORT_TELNET:
                $socketRequestService = $this->container->get(Constants\ServicesNames::SocketRequestService);           
                $result= $socketRequestService->query($config['host'], $config['port'], DeviceHelper::getMultimediaCommand($config['command'], $command));
                break;
        }
        //Command Set Volume
        if($command == Constants\Devices::COMMAND_VOLSET){
            $volSelected= intval($options[0]);
            $volResult= DeviceHelper::getVolumeValue($result);
            usleep(150000);
            if($volSelected<$volResult){    
                $result= $this->sendMultimediaCommand($deviceIdentifier, Constants\Devices::COMMAND_VOLDOWN);
            }else if($volSelected>$volResult){
                $result= $this->sendMultimediaCommand($deviceIdentifier, Constants\Devices::COMMAND_VOLUP);
            }
            $volResult= DeviceHelper::getVolumeValue($result);  
            if($volResult!=$volSelected){
                usleep(150000);
                $result = $this->sendMultimediaCommand($deviceIdentifier, Constants\Devices::COMMAND_VOLSET, $options);
            }
        }
        if($command== Constants\Devices::COMMAND_OFF){
            return $command;
        }
        return Constants\Devices::COMMAND_ON;
    }
    
    private function sendZwaveCommand($deviceIdentifier, $command)
    {
        $zwaveRequestService = $this->container->get(Constants\ServicesNames::ZwaveRequestService);
        $idx= substr($deviceIdentifier, 6);
        $Zcommand="";
        switch ($command){
            case Constants\Devices::COMMAND_ON:
            case Constants\Devices::COMMAND_OFF:
                $Zcommand= "Control.".$command;
                break;
        }
        $params= array($idx, $Zcommand);
        $zwaveRequestService->sendQuery($params);
        return $command;
        
    }
    
    public function loadConfiguration(){
        $deviceRepository = $this->getRepository();
        
        $configPCs = $this->container->getParameter(Constants\Devices::CONFIG_PCS);
        foreach ($configPCs as $pc_name=>$config)
        {
            $tempDevice = new Device();
            $tempDevice->setIdentifier($pc_name);
            $tempDevice->setName($pc_name);
            $tempDevice->setDeviceType(Constants\Devices::TYPE_PC);
            $tempDevice->setCreated(new \DateTime());
            $tempDevice->setUpdated(new \DateTime());
            $deviceRepository->createOrUpdate($tempDevice);
        }
        
        $configDevices = $this->container->getParameter(Constants\Devices::CONFIG);
        foreach ($configDevices as $pc_name=>$config)
        {
            $tempDevice = new Device();
            $tempDevice->setIdentifier($pc_name);
            $tempDevice->setName($pc_name);
            $tempDevice->setDeviceType(Constants\Devices::TYPE_MULTIMEDIA);
            $tempDevice->setCreated(new \DateTime());
            $tempDevice->setUpdated(new \DateTime());
            $deviceRepository->createOrUpdate($tempDevice);
        }
        
        $zwaveRequestService = $this->container->get(Constants\ServicesNames::ZwaveRequestService);
        $zwaveDevices= $zwaveRequestService->getModulesList();
        foreach ($zwaveDevices as $device){
            $tempDevice = new Device();
            $tempDevice->setIdentifier(Constants\Devices::TYPE_ZWAVE."_".$device->Address);
            $tempDevice->setName($device->Name);
            $deviceType= Constants\Devices::TYPE_ZWAVE;
            $deviceType.="_". strtolower($device->DeviceType);
            $tempDevice->setDeviceType($deviceType);
            $tempDevice->setCreated(new \DateTime());
            $tempDevice->setUpdated(new \DateTime());
            $deviceRepository->createOrUpdate($tempDevice);
        }
    }
    
    private function getRepository(){
        return $this->container->get(Constants\ServicesNames::DeviceRepository);
    }
}
