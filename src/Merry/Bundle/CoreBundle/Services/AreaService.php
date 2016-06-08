<?php
namespace Merry\Bundle\CoreBundle\Services;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Merry\Bundle\CoreBundle\Constants;
use Merry\Bundle\CoreBundle\AccessObject\Response;
use Merry\Bundle\CoreBundle\Helper\AreaHelper;

class AreaService {
    
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
        $areas = $this->getRepository()->getAll();
        $result=array();
        foreach ($areas as $area)
        {
            $result[]= AreaHelper::mapToRessource($area);
        }
        $response->setSuccess($result);
        return $response;
    }
    
     private function getRepository(){
        return $this->container->get(Constants\ServicesNames::AreaRepository);
    }
    
    
}
