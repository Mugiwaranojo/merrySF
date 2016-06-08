<?php

namespace Merry\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Merry\Bundle\CoreBundle\Constants;

class RequestServiceTest extends WebTestCase
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
    
    public function testSocketRequestService()
    {
       $socketRequestService = self::$container->get(Constants\ServicesNames::SocketRequestService);
       $configDevices = self::$container->getParameter(Constants\Devices::CONFIG);
       $configAmpli = $configDevices['ampliVSX'];
       
       $result = $socketRequestService->query($configAmpli['host'], $configAmpli['port'], Constants\DevicesPioneer::COMMAND_VOLUP);
       
       $this->assertRegExp('/VOL[0-9]+/', $result);
    }
    
    public function testHttpBasicRequestService()
    {
       $httpRequestService = self::$container->get(Constants\ServicesNames::HttpBasicRequestService);
       $configDevices = self::$container->getParameter(Constants\Devices::CONFIG);
       $configTV = $configDevices['tvToshiba'];
       
       $data= array('path'=>$configTV['path'],
                    'method'=>'POST',
                    'username'=>$configTV['username'],
                    'password'=>$configTV['password'],
                    'values'=>array('key' => Constants\DevicesToshiba::COMMAND_VOLUP));
       $result = $httpRequestService->query($configTV['host'], $configTV['port'], $data);
       $resultJson= json_decode($result);
       
       $this->assertEquals(0, $resultJson->status);
    }
    
    public function testZwaveRequestService(){
        $zwaveRequestService = self::$container->get(Constants\ServicesNames::ZwaveRequestService);
        $params= array(7, "Control.On");
        $result = $zwaveRequestService->sendQuery($params);
    }
}
