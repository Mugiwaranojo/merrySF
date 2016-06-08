<?php

namespace Merry\Bundle\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Merry\Bundle\CoreBundle\Constants;

class PCServiceTest extends WebTestCase
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
    
//    public function testPCServiceSleep()
//    {
//       $pcService = self::$container->get(Constants\ServicesNames::PCService);
//       $result = $pcService->goToSleep('vogue_merry');
//       
//       $this->assertEquals(true, $result);
//    }
    
    public function testPCServiceWakeUp()
    {
       $pcService = self::$container->get(Constants\ServicesNames::PCService);
       $result = $pcService->wakeOnLan('vogue_merry');
       $this->assertEquals(0, $result);
    }
}
