<?php

namespace Merry\Bundle\CoreBundle\Services;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Merry\Bundle\CoreBundle\Interfaces\IRequestService;

class SSHRequestService implements IRequestService {
    
    private $logger;
    
    public function __construct(LoggerInterface $logger)
    {
       $this->logger = $logger;
    }
    
    public function query($cfgHost, $cfgPort, $data = null) {
        
        if(!isset($data['command']) || !isset($data['username'])  || !isset($data['password']))
        {
            $this->logger->error(sprintf("Error : data argument(s) missing."));
            return null;
        }
        
        $connection = ssh2_connect($cfgHost, $cfgPort);
        ssh2_auth_password($connection, $data['username'], $data['password']);
        if(!$connection)
        {
            $this->logger->error(sprintf("Error : cannot connec to %s", $cfgHost));
            return null;
        }
        $stream = ssh2_exec($connection, $data['command']);
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

        // Desable blocking for both streams
        stream_set_blocking($errorStream, false);
        stream_set_blocking($stream, false);

        // Whichever of the two below commands is listed first will receive its appropriate output.  The second command receives nothing
        $output= stream_get_contents($stream);
        $error= stream_get_contents($errorStream);
        if($error)
        {
            $this->logger->error(sprintf("Error :  %s", $errorStream));
        }
        // Close the streams       
        fclose($errorStream);
        fclose($stream);
        
        if($output)
        {
           return $output;
        }
        else
        {
            return true;
        }
    }

}
