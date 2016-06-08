<?php
namespace Merry\Bundle\CoreBundle\Services;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Merry\Bundle\CoreBundle\Constants;
use Merry\Bundle\CoreBundle\AccessObject\Response;

class ListenService {
    
    private $logger;
    private $container;
    
    public function __construct(LoggerInterface $logger, $container)
    {
       $this->logger = $logger;
       $this->container = $container;
    }
    
    private function getPrograms(){
        return array("vlc"=>"VLC",
                     "skybox"=>"la skybox",
                     "firefox"=>"Firefox",
                     "mediaGo"=>"Media Go");
    }
    
    private function getChannels(){
        return $this->getChannelRepository()->getAll();
    }
    
    private function getDevices(){
        return array("vogue_merry"=>"mairie",
                     "thousand_sunny"=>"Sony");
    }
    
    public function receive($sentences, $areaId){
        $response = new Response();
        if(empty($sentences)){
            $response->code= Constants\HttpStatusCodes::BAD_REQUEST;
            $response->message= sprintf("sentences array is empty");
            $response->updateData();
            return $response;
        }
        $result="";
        foreach ($sentences as $sentence){
            $args="";
            $deviceArg=$this->getSentenceDeviceArg($sentence);
            $args.=$this->getSentenceChannelArg($sentence);
            $args.=$this->getSentenceProgramArgs($sentence);
            $args.=$this->getSentenceIntArgs($sentence);
            $expressions = $this->getExpressionRepository()->findBySentence($sentence);
            foreach ($expressions as $expression){
                $result=$this->receiveExpression($expression, $areaId, $deviceArg, $args);
            }
        }
        $response->setSuccess($result);
        return $response;
    }
    
    private function getSentenceIntArgs(&$sentence){
        $args="";
        if(preg_match_all("/[0-9]+/", $sentence, $matches)){
            foreach ($matches as $value){
                $sentence= str_replace($value, "<int>", $sentence);
                $args.= $value[0].";";
            }
        }
        return $args;
    }
    
    private function getSentenceProgramArgs(&$sentence){
        $args="";
        foreach ($this->getPrograms() as $key => $value){
            if(preg_match("/$value/", $sentence)){
                $sentence= str_replace($value, "<program>", $sentence);
                $args= $key;
            }
        }
        return $args;
    }
    
    private function getSentenceDeviceArg(&$sentence){
        $args="";
        foreach ($this->getDevices() as $key => $value){
            if(preg_match("/$value/", $sentence)){
                $sentence= str_replace($value, "<device>", $sentence);
                $args= $key;
            }
        }
        return $args;
    }
    
    private function getSentenceChannelArg(&$sentence){
        $args="";
        foreach ($this->getChannels() as $channel){
            if(preg_match("/".$channel->getRecognizervalue()."/", $sentence)){
                $sentence= str_replace($channel->getRecognizervalue(), "<channel>", $sentence);
                $args= $channel->getProgram().";".urlencode($channel->getValue());
            }
        }
        return $args;
    }
            
    
    private function receiveExpression($expression, $areaId, $deviceArg, $args){
        $returnText="";
        foreach ($expression->getActions() as $expressionAction){
            if($areaId==1 || $areaId==$expressionAction->getArea()->getId()){
                $result= $this->releaseAction($expressionAction->getAction(), $deviceArg, $args);
                if($result->code==200 && $expression->getSentenceType()=="order"){
                    $returnText="C'est fait.";
                }else if($result->code==200 && $expression->getSentenceType()=="ordertts"){
                    $returnText= $result->result;
                }
            }
        }
        return $returnText;
    }
    
    private function releaseAction(\Merry\Bundle\CoreBundle\Entity\Action $action, $deviceArg, $args){
        $devicesActions= $this->getDevicesActionsRepository()->getByActionId($action->getId());
        $deviceService = $this->container->get(Constants\ServicesNames::DeviceService);
        $result=null;
        foreach($devicesActions as $deviceAction){
            if(!empty($result)&& $result->code!=200){
                return $result;
            }
            if($deviceAction->getDelay()){
                sleep($deviceAction->getDelay());
            }
            $deviceIdentifier = $deviceAction->getDevice()->getIdentifier();
            if(!empty($deviceArg)){
                $deviceIdentifier= $deviceArg;
            }
            $command= $deviceAction->getCommand();
            $options = $deviceAction->getArgs();
            if(!empty($args)){
                $options=$args;
            }
            if($options){
                $options= explode(";", $options);
            }
            
            if(count($options)>1 && $options[0]=='skybox'){
                $result= $this->sendKeyForChannel($deviceService, $deviceIdentifier, $options);
            }else if($command=="says"){
                $deviceService->sendCommand($deviceIdentifier, $command, $options);
                if($options[0]=="InfoService"){
                    $info= $this->getHttpRequestService()->query("localhost", 80, array('path'=>"/info/tts/".$options[1],
                                                                                'method'=>'GET',
                                                                                'username'=>"",
                                                                                'password'=>""));
                    $jsonData= json_decode($info);
                    $result= $jsonData;
                }
            }else{
                $result= $deviceService->sendCommand($deviceIdentifier, $command, $options);
            }
        }
        return $result;
    }
    
    private function getHttpRequestService()
    {
        return $this->container->get(Constants\ServicesNames::HttpBasicRequestService);
    }
    
    private function sendKeyForChannel($deviceService, $deviceIdentifier, $options){
        $result=false;
        for($i=0; $i<strlen($options[1]); $i++){
            $result= $deviceService->sendCommand($deviceIdentifier, Constants\Devices::COMMAND_SENDKEY, array($options[1][$i]));
        }
        return $result;
    }
    
    private function getChannelRepository(){
        return $this->container->get(Constants\ServicesNames::ChannelRepository);
    }
    
    private function getExpressionRepository(){
        return $this->container->get(Constants\ServicesNames::ExpressionRepository);
    }
    
    private function getDevicesActionsRepository(){
        return $this->container->get(Constants\ServicesNames::DevicesActionsRepository);
    }
}
