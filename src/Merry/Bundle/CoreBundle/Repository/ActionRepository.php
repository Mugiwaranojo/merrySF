<?php

namespace Merry\Bundle\CoreBundle\Repository;
 
use Doctrine\ORM\EntityRepository;
use Merry\Bundle\CoreBundle\Entity\Action;
use Merry\Bundle\CoreBundle\Repository\DevicesActionsRepository;

class ActionRepository extends EntityRepository
{
    
    private $devicesActionsRepository;
    
    public function __construct($em, $class) {
        parent::__construct($em, $class);
        $this->devicesActionsRepository = $this->getEntityManager()
                                               ->getRepository('MerryCoreBundle:DevicesActions');
    }
    
    public function find($name=null) {
        if(!$name)
        {
            $search = $this->findAll();
        }
        else
        {
            $search = $this->findBy(array("name"=>$name));
        }
        foreach($search as $key=>$action)
        {
            $search[$key]->setDevicesActions($this->devicesActionsRepository->getByActionId($action->getId()));
        }
        return $search;
    } 
}
