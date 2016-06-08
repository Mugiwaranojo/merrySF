<?php

namespace Merry\Bundle\CoreBundle\Services;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Merry\Bundle\CoreBundle\Constants;

class PCService {
    
    private $logger;
    private $container;
    
    public function __construct(LoggerInterface $logger, $container)
    {
       $this->logger = $logger;
       $this->container = $container;
    }
    
    public function sendCommand($pcName, $command){
        $configPc= $this->getPcConfig($pcName);
        if(!$configPc)
        {
            $this->logger->err(sprintf("Error : %s is not config", $pcName));
            return false;
        }
        
        $sshService = $this->getSSHRequestService();
        $data=array("username"=>$configPc['ssh_username'],
                    "password"=>$configPc['ssh_password'],
                    "command"=> $command);
        return $sshService->query($configPc['host'], 22, $data);
    }
    
    public function sendClientRequest($pcName, $command, $options=null)
    {
        $configPc= $this->getPcConfig($pcName);
        if(!$configPc)
        {
            $this->logger->err(sprintf("Error : %s is not config", $pcName));
            return false;
        }
        
        $params= "command=".$command."&value=".$options[0];
        
        if($options[0]=="InfoService"){
            $info= $this->getHttpRequestService()->query("localhost", 80, array('path'=>"/info/tts/".$options[1],
                                                                            'method'=>'GET',
                                                                            'username'=>"",
                                                                            'password'=>""));
            $jsonData= json_decode($info);
            $params= "command=".$command."&value=".urlencode($jsonData->result);
        }else if(count($options)>1){
            for($i=1;$i<count($options);$i++){
                $params .= "&options[]=".$options[$i];
            } 
        }
        $dataHttp= array('path'=>"/?".$params,
                    'method'=>'GET',
                    'username'=>"",
                    'password'=>"");
        return $this->getHttpRequestService()->query($configPc['host'], 9090, $dataHttp);
    }
    
    public function goToSleep($pcName){
        return $this->sendCommand($pcName, "shutdown.exe /h");
    }
    
    public function wakeOnLan($pcName){
        $configPc= $this->getPcConfig($pcName);
        if(!$configPc)
        {
            $this->logger->err(sprintf("Error : %s is not config", $pcName));
            return false;
        }
        
        $err = 0;
        $mySocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (!$mySocket)
        {
            $err = 3; //test of socket creation.
            $this->logger->err(sprintf("Error %d : socket cannot be created", $err));
        }
        else
        {
            $setOpt = socket_set_option($mySocket, SOL_SOCKET, SO_BROADCAST, 1);
            if($setOpt < 0){
                $err = 4;
                $this->logger->err(sprintf("Error %d : socket cannot be open", $err));
            }
            else
            {
                $magicPacket = $this->packet($configPc['mac']);
                if(!socket_sendto($mySocket, $magicPacket, strlen($magicPacket), 0, $configPc['host'], 9))
                {
                    $err = 5;
                    $this->logger->err(sprintf("Error %d : packet cannot be send", $err));
                }
            }
        }
        return $err;
    }
    
    private function packet($mac){
        $packet = "";
        $addrByte = explode(':', $mac); 
        $macAddr = '';
        $magicPacket = '';

        for ($i=0; $i <6; $i++)
            $macAddr .= chr(hexdec($addrByte[$i]));
        for($i = 0; $i < 6; $i++)
            $magicPacket.= chr(0xFF);
        for ($j = 0; $j < 16; $j++)
        {
            $magicPacket .= $macAddr;
        }
        return $magicPacket; 
    }
    
    private function getSSHRequestService()
    {
        return $this->container->get(Constants\ServicesNames::SSHRequestService);
    }
    
    private function getHttpRequestService()
    {
        return $this->container->get(Constants\ServicesNames::HttpBasicRequestService);
    }
    
    private function getPcConfig($pcName)
    {
       $configPCs= $this->container->getParameter(Constants\Devices::CONFIG_PCS);
       return isset($configPCs[$pcName])?$configPCs[$pcName]:null;
    }
}
