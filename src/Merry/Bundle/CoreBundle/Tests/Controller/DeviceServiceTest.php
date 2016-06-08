<?php

namespace Merry\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Merry\Bundle\CoreBundle\Constants;


class DeviceServiceTest extends WebTestCase
{
    private static $container;
    
    public static function setUpBeforeClass()
    {
         //start the symfony kernel
         $kernel = static::createKernel();
         $kernel->boot();

         //get the DI container
         self::$container = $kernel->getContainer();
    }
    
    public function testLoadDevices(){
        $deviceService = self::$container->get(Constants\ServicesNames::DeviceService);
        $deviceService->loadConfiguration();
        
        $allDevices = $deviceService->getAll();
        $this->assertEquals(9, count($allDevices));
    }
    
    public function testAllTurnOn(){
        $deviceService = self::$container->get(Constants\ServicesNames::DeviceService);
        $deviceService->allTurnOn();
    }
    
}
