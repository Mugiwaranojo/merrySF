<?php

namespace Merry\Bundle\CoreBundle\Repository;
 
use Doctrine\ORM\EntityRepository;
use Merry\Bundle\CoreBundle\Entity\Device;

class DeviceRepository  extends EntityRepository{
   
    public function getAll()
    {
        $result= $this->findAll();
        $allDevices= array();
        foreach ($result as $key=>$device){
            if(!isset($allDevices[$device->getArea()->getId()])){
                $allDevices[$device->getArea()->getId()]= array();
            }
            $device->setDevicesChilds($this->getChildById($device->getId()));
            $allDevices[$device->getArea()->getId()][]= $device;
        }
        $results= array();
        sort($allDevices);
        foreach ($allDevices as $areaDevices){
            foreach ($areaDevices as $device){
                $results[]=$device;
            }
        }
        return $results;
    }
    
    public function getByIdentifier($identifier)
    {
        $result = $this->findBy(array('identifier'=>$identifier));
        if(!empty($result))
        {
            $result[0]->setDevicesChilds($this->getChildById($result[0]->getId()));
        }
        return count($result)==1? $result[0] : null;
    }
    
    public function getChildById($deviceParentId)
    {
        $result = $this->findBy(array('parent'=>$deviceParentId));
        return $result;
    }
    
    public function createOrUpdate(Device $device)
    {
        $em= $this->getEntityManager();
            
        $savedDevice= $this->getByIdentifier($device->getIdentifier());
        if($savedDevice)
        {
           $savedDevice->setStatus($device->getStatus());
           $savedDevice->setParent($device->getParent());
           $savedDevice->setDeviceType($device->getDeviceType());
           $savedDevice->setName($device->getName());
           $savedDevice->setActive($device->getActive());
           $savedDevice->setFavorite($device->getFavorite());
           $savedDevice->setUpdated($device->getUpdated());
           $em->persist($savedDevice);
           $em->flush();
        }
        $em->persist($device);
        $em->flush($device);
    }
}
