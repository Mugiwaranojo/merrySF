<?php
namespace Merry\Bundle\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ChannelRepository extends EntityRepository {
    
    public function getAll(){
        return $this->findAll();
    }
    
    public function getByName($name){
        return $this->findBy(array("name"=>$name));
    }
}
