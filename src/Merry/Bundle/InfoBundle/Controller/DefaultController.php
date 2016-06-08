<?php

namespace Merry\Bundle\InfoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Merry\Bundle\InfoBundle\Constants\ServicesNames;
use Merry\Bundle\CoreBundle\AccessObject\Response;
use Merry\Bundle\CoreBundle\Constants\HttpStatusCodes;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $response = new Response();
        switch ($name){
            case 'salat':
                $alKanzService= $this->get(ServicesNames::AlKanzService);
                $args= array("noisy-le-sec","93130", date("F"));
                $info= $alKanzService->getInformation($args);
                $response->setSuccess($info);
                break;
            case 'programmetv':
                $programmeTVService= $this->get(ServicesNames::ProgrammeTVService);
                $args= array("canalsat-5","en-ce-moment.html");
                $info= $programmeTVService->getInformation($args);
                $response->setSuccess($info);
                break;
            default:
                $response->code= HttpStatusCodes::SERVICE_UNAVAILABLE;
                $response->message= "Service info $name is unavaible";
                $response->updateData();
                break;
        }
        return $response;
    }
    
    public function ttsAction($name)
    {
        $response = new Response();
        switch ($name){
            case 'salat':
                $alKanzService= $this->get(ServicesNames::AlKanzService);
                $args= array("noisy-le-sec","93130", date("F"));
                $info= $alKanzService->getSalatInfoForTTS($args);
                $response->setSuccess($info);
                break;
            case 'nextsalat':
                $alKanzService= $this->get(ServicesNames::AlKanzService);
                $args= array("noisy-le-sec","93130", date("F"));
                $info= $alKanzService->getSalatInfoForTTS($args, true);
                $response->setSuccess($info);
                break;
            case 'programmetv':
                $programmeTVService= $this->get(ServicesNames::ProgrammeTVService);
                $args= array("canalsat-5","en-ce-moment.html");
                $info= $programmeTVService->getInformationProgrammesForTTS($args);
                $response->setSuccess($info);
                break;
            default:
                $response->code= HttpStatusCodes::SERVICE_UNAVAILABLE;
                $response->message= "Service info $name is unavaible";
                $response->updateData();
                break;
        }
        return $response;
    }
}
