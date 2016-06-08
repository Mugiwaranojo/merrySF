<?php

namespace Merry\Bundle\CoreBundle\Repository;
 
use Doctrine\ORM\EntityRepository;

class DevicesActionsRepository extends EntityRepository 
{
    public function getByActionId($actionId)
    {
        return $this->findBy(array("action"=>$actionId));
    }
}
