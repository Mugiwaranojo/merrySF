<?php

namespace Merry\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Merry\Bundle\CoreBundle\Constants;
use Merry\Bundle\CoreBundle\Entity\Action;

class ActionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('merry:action')
            ->setDescription('Do configured actions')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of action')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $deviceService = $this->getContainer()->get(Constants\ServicesNames::DeviceService);
        $actionRepository = $this->getContainer()->get(Constants\ServicesNames::ActionRepository);
        if(!$name){
            $actionList= $actionRepository->find();
        }else{
            $actionList= $actionRepository->find($name);
        }
        foreach ($actionList as $action){
            if($action->getName()==="SayNextSalatTime" && $action->getActive()){
                $httpService= $this->getContainer()->get(Constants\ServicesNames::HttpBasicRequestService);
                $info= $httpService->query("localhost", 80, array('path'=>"/info/tts/nextsalat",
                                                                                    'method'=>'GET',
                                                                                    'username'=>"",
                                                                                    'password'=>""));
                $jsonData= json_decode($info);
                if(!preg_match("/dans 10 minutes/", $jsonData->result) && !$name){
                    continue;
                }
            }else if(!$action->getActive() || (!$this->isTimeToExec($action)&&!$name)){
                continue;
            }
            $output->writeln(sprintf('Exec action %s', $action->getName()));
            foreach($action->getDevicesActions() as $deviceAction){
                if($deviceAction->getDelay()){
                    sleep($deviceAction->getDelay());
                }
                $deviceIdentifier = $deviceAction->getDevice()->getIdentifier();
                $command= $deviceAction->getCommand();
                $options = $deviceAction->getArgs();
                if($options){
                    $options= explode(";", $options);
                }
                $deviceService->sendCommand($deviceIdentifier, $command, $options);
            }
        }
    }
    
    private function isTimeToExec(Action $action)
    {
        if($action->getFrequency()== Constants\Actions::FREQUENCY_EVERYDAY)
        {
            $currentTime= new \DateTime();
            return $action->getStarted() && $currentTime->format("H:i") == $action->getStarted()->format("H:i");
                
        }
        return false;
    }
}
