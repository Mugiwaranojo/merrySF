<?php

namespace Merry\Bundle\InfoBundle\Services;

use \Merry\Bundle\InfoBundle\AccessObject\AlKanzInfo;

class AlKanzService  extends AbstractInfoService{
    
    public $urlInfo="www.al-kanz.org/horaire-de-priere";
    
    /*
     * return AlKanzInfo
     */
    public function getInformation($args) {
        
        $pageInfo = parent::getPageInfo($args);
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($pageInfo);
        libxml_clear_errors();
        
        $xpath = new \DOMXpath($doc);
        $elements = $xpath->query("//*[@class='timetable']");
        $timeTable= $elements->item(0);
        $timeTableLines= $xpath->query("tr", $timeTable);
        $alkanz = new AlKanzInfo();
        for($i=0; $i<$timeTableLines->length; $i++){
            $timeLine= $timeTableLines->item($i);
            if(preg_match("/today/", $timeLine->getAttribute('class'))){
                $infos = explode("\n", $timeLine->textContent);
                $alkanz->localisation= $args[0];
                $alkanz->dateIslam= $infos[0];
                $alkanz->dateGregorienne= $infos[1];
                $alkanz->subh= $infos[2];
                $alkanz->shuruq= $infos[3];
                $alkanz->zhur= $infos[4];
                $alkanz->asr= $infos[5];
                $alkanz->magrib= $infos[6];
                $alkanz->isha= $infos[7];
                return $alkanz;
            }
        }
        return $alkanz;
    }
    
    public function getSalatInfoForTTS($args, $onlyNext=false){
        $alkanzInfo= $this->getInformation($args);
        //Instantiate the reflection object
        $reflector = new \ReflectionClass('\Merry\Bundle\InfoBundle\AccessObject\AlKanzInfo');
        $properties = $reflector->getProperties();
        if($onlyNext){
            $text= "La prochaine prière est ";
            $nextSalatData= $this->getNextSalat($alkanzInfo, $properties);
            $text.=$nextSalatData["name"]. " à ". $this->timeToTimeSpeak($nextSalatData["time"]);
            $text.=" qui est dans ". $this->timeToTimeSpeak($nextSalatData["interval"], true);
        }else{
            $text="Les heures de prières pour aujourd'hui ".$alkanzInfo->dateGregorienne." sont ";
            $i =0;
            foreach($properties as $property)
            {
                if($i>2){
                    if($i>3)$text.=", ";
                    if($i==count($properties)-1)$text.="et ";
                    $text.= $property->getName(). " à ". $this->timeToTimeSpeak($alkanzInfo->{$property->getName()});
                }
                $i++;
            }
        }
        return $text;
        
    }
    
    private function timeToTimeSpeak($value, $isInterval=false){
        $dateTime= new \DateTime($value);
        $text = $dateTime->format("H"). " heures ". $dateTime->format("i");
        if($isInterval){
            return str_replace("00 heures", "", $text). " minutes";
        }else{
            return $text;
        }
    }
    
    private function getNextSalat(AlKanzInfo $alkanzInfo, array $properties){
        $dataSalatTime=array();
        $i =0;
        foreach($properties as $property)
        {
            if($i>2){  
                $dataSalatTime=$alkanzInfo->{$property->getName()};
                $dataTabSalatTime= explode(":", $dataSalatTime);
                if($dataTabSalatTime[0]<date("H") || ($dataTabSalatTime[0]==date("H") && $dataTabSalatTime[1]<=date("I"))){
                    continue;
                }else{
                    $valSalatTime= date("Y-m-d ")." ".$dataSalatTime.":00.000";
                    $dateTimeSalat= new \DateTime($valSalatTime);
                    $dateTimeNow= new \DateTime("now");
                    $interval = $dateTimeNow->diff($dateTimeSalat);
                    return array("name"=>$property->getName(),
                                 "time"=> $dataSalatTime,
                                 "interval"=>$interval->format('%h:%i'));
                    
                }
            }   
            $i++;
        }
        return null;
    }
}
