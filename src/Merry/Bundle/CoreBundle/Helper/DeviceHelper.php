<?php

namespace Merry\Bundle\CoreBundle\Helper;

use Merry\Bundle\CoreBundle\Constants;
use \Merry\Bundle\CoreBundle\Entity\Device;

class DeviceHelper 
{
    public static function mapToRessource(Device $device){
        $ressource = new \Merry\Bundle\CoreBundle\AccessObject\Ressources\Device();
        $ressource->id= $device->getId();
        $ressource->parentId= $device->getParent() ? $device->getParent()->getId() : null; 
        $ressource->identifier= $device->getIdentifier();
        $ressource->name= $device->getName();
        $ressource->status= $device->getStatus();
        $ressource->deviceType= $device->getDeviceType();
        $ressource->updated= $device->getUpdated()->format("Y-m-d H:i:s");
        $ressource->created= $device->getCreated()->format("Y-m-d H:i:s");
        return $ressource;
    }
    
    public static function getMultimediaCommand($product, $command){
        switch ($product)
        {
            case Constants\DevicesPioneer::NAME:
                switch ($command)
                {
                    case Constants\Devices::COMMAND_ON:
                        return Constants\DevicesPioneer::COMMAND_ON;
                    case Constants\Devices::COMMAND_OFF:
                        return Constants\DevicesPioneer::COMMAND_OFF;
                    case Constants\Devices::COMMAND_MUTEON:
                        return Constants\DevicesPioneer::COMMAND_MUTEON;
                    case Constants\Devices::COMMAND_MUTEOFF:
                        return Constants\DevicesPioneer::COMMAND_MUTEOFF;
                    case Constants\Devices::COMMAND_VOLUP:
                        return Constants\DevicesPioneer::COMMAND_VOLUP;
                    case Constants\Devices::COMMAND_VOLDOWN:
                        return Constants\DevicesPioneer::COMMAND_VOLDOWN;
                     case Constants\Devices::COMMAND_VOLSET:
                        return Constants\DevicesPioneer::COMMAND_VOLSET;
                    case Constants\Devices::COMMAND_SOURCE:
                        return Constants\DevicesPioneer::COMMAND_SOURCE;
                }
                break;
            case Constants\DevicesToshiba::NAME:
                switch ($command)
                {
                    case Constants\Devices::COMMAND_ON:
                        return Constants\DevicesToshiba::COMMAND_ON;
                    case Constants\Devices::COMMAND_OFF:
                        return Constants\DevicesToshiba::COMMAND_OFF;
                    case Constants\Devices::COMMAND_VOLUP:
                        return Constants\DevicesToshiba::COMMAND_VOLUP;
                    case Constants\Devices::COMMAND_VOLDOWN:
                        return Constants\DevicesToshiba::COMMAND_VOLDOWN;
                    case Constants\Devices::COMMAND_SOURCE:
                        return Constants\DevicesToshiba::COMMAND_SOURCE;
                }
                break;
        }
        return $command;
    }
    
    public static function getVolumeValue($string){
        $volResult= intval(substr($string, 3))/2;
        if($volResult%5!=0){
            $volResult= $volResult - 0.5;
        }
        return intval($volResult);
    }
}
