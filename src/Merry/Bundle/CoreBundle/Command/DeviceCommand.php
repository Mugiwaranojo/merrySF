<?php

namespace Merry\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Merry\Bundle\CoreBundle\Constants;

class DeviceCommand extends ContainerAwareCommand 
{
    protected function configure()
    {
        $this
            ->setName('merry:device')
            ->setDescription('Send command to device')
            ->addArgument('name', InputArgument::REQUIRED, 'Identifier of device')
            ->addArgument('value', InputOption::VALUE_REQUIRED, 'command to send to device')
            ->addArgument('options', InputOption::VALUE_OPTIONAL, 'options of command to send to device')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $command = $input->getArgument('value');
        $options = $input->getArgument('options');
        
        $deviceService= $this->getContainer()->get(Constants\ServicesNames::DeviceService);
        if ($name==Constants\Devices::COMMAND_ALL) {
            switch ($command){
                case Constants\Devices::COMMAND_ON;
                    $deviceService->allTurnOn();
                    break;
                case Constants\Devices::COMMAND_OFF;
                    $deviceService->allTurnOff();
                    break;
            }
        } else {
            $deviceService->sendCommand($name, $command, $options);
        }
        //$output->writeln($text);
    }
}
