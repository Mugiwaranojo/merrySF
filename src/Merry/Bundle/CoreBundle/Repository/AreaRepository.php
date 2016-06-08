<?php
namespace Merry\Bundle\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AreaRepository  extends EntityRepository
{
    public function getAll(){
        return $this->findAll();
    }
}
