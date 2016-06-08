<?php

namespace Merry\Bundle\InfoBundle\Services;
use Merry\Bundle\InfoBundle\AccessObject\ProgrammeTVInfo;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class ProgrammeTVService extends AbstractInfoService {
    
    public $urlInfo="www.programme-tv.net/programme";
    
    private $logger;
    private $container;
    
    public function __construct(LoggerInterface $logger, $container)
    {
       $this->logger = $logger;
       $this->container = $container;
    }
    
    public function getInformation($args) {
        $pageInfo = parent::getPageInfo($args);
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($pageInfo);
        libxml_clear_errors();
        
        $xpath = new \DOMXpath($doc);
        $elements = $xpath->query("//div[@class='block programme']");
        $resutList= array();
        //parcours des programmes
        for($n=0;$n<$elements->length;$n++){
            $programmeTable= $elements->item($n);
            $programmeTableChilds= $xpath->query("div", $programmeTable);
            //parcours des chaines
            for($i=0; $i<$programmeTableChilds->length; $i++){
                $channelLine= $programmeTableChilds->item($i);
                if(preg_match("/channel/", $channelLine->getAttribute('class'))){
                    //recherche informations des chaines
                    $infoChannel= $xpath->query("div", $channelLine);
                    $programmeInfo= new ProgrammeTVInfo();
                    for($j=0; $j<$infoChannel->length; $j++){
                        if(preg_match("/channelItem/", $infoChannel->item($j)->getAttribute('class'))){
                            $programmeInfo->channelName= trim(str_replace("Programme", "", $infoChannel->item($j)->textContent));
                        }else if(preg_match("/at-the-moment/", $infoChannel->item($j)->getAttribute('class'))){
                            $tempInfo = $xpath->query("div", $infoChannel->item($j));
                            for($k=0;$k<$tempInfo->length;$k++){
                                if($tempInfo->item($k)->getAttribute('class')=="show-infos"){
                                    $tempInfoData = $xpath->query("p", $tempInfo->item($k));
                                    $programmeInfo->programmeTime= $tempInfoData->item(0)->textContent;
                                    $programmeInfo->programmeTitle= $tempInfoData->item(1)->textContent;
                                    $programmeInfo->programmeType= $tempInfoData->item(2)->textContent;
                                }
                            }
                        }
                    }
                    if($programmeInfo->channelName){
                        $resutList[]=$programmeInfo;
                    }
                }
            }
        }
        return $resutList;
    }
    
    public function getInformationProgrammesForTTS($args){
        $tabProgrammes = $this->getInformation($args);
        $filtredChannelList= array();
        $text= "";
        foreach ($tabProgrammes as $programmeInfo){
            $channels= $this->getChannelRepository()->getByName($programmeInfo->channelName);
            foreach ($channels as $channel){
                if($channel->getIsfavorite()){
                    $filtredChannelList[]= $programmeInfo;
                }
            }
        }
        
        foreach($filtredChannelList as $programmeInfo){
            $text.= "Sur ".$programmeInfo->channelName.", ".$programmeInfo->programmeTitle.". ";
        }
        return $text;
    }
    
    private function getChannelRepository(){
        return $this->container->get(\Merry\Bundle\CoreBundle\Constants\ServicesNames::ChannelRepository);
    }
}
