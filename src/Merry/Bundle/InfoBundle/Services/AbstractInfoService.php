<?php
namespace Merry\Bundle\InfoBundle\Services;


abstract class AbstractInfoService {
    
    public $urlInfo="";
    
    public function getPageInfo( $args ){
        $url = $this->urlInfo . '/' . implode('/', $args);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    
    abstract protected function getInformation($args); 
    
}
