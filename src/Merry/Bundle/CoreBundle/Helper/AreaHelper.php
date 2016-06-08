<?php
namespace Merry\Bundle\CoreBundle\Helper;

use \Merry\Bundle\CoreBundle\Entity\Area;

class AreaHelper {
    
    public static function mapToRessource(Area $area){
        $ressource = new \Merry\Bundle\CoreBundle\AccessObject\Ressources\Area();
        $ressource->id= $area->getId();
        $ressource->name= $area->getName();
        $ressource->location= array("name"=>$area->getLocation()->getName(),
                                    "longitude"=>$area->getLocation()->getLongitude(),
                                    "latitude"=>$area->getLocation()->getLatitude());
        $ressource->updated= $area->getUpdated()->format("Y-m-d H:i:s");
        $ressource->created= $area->getCreated()->format("Y-m-d H:i:s");
        return $ressource;
    }
}
