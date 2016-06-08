<?php
namespace Merry\Bundle\CoreBundle\Services;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Merry\Bundle\CoreBundle\Interfaces\IRequestService;

class SocketRequestService implements IRequestService
{
    private $logger;
    
    public function __construct(LoggerInterface $logger)
    {
       $this->logger = $logger;
    }
    
    /*
     * Envoi une requete a un server
     * @param string $cfgHost ip host
     * @param int $cfgHost port host
     * @param mixed $data params to send
     * @return $mixed result of $request;
     */
    public function query($cfgHost, $cfgPort, $data=null)
    {
        $usenet = fsockopen($cfgHost, $cfgPort, $errno, $errstr);
        $result = null;
        if(!$usenet)
        {
            $this->logger->error(sprintf("Connexion failed\nErrNo: %s\nErrMsg: %s", $errno, $errstr));
        }
        else
        {
            $this->logger->info(sprintf("Connected to %s:%s", $cfgHost, $cfgPort));
            if($data)
            {
                fwrite($usenet, "$data\r\n");
                $result= fgets($usenet)."\n";
                $this->logger->info(sprintf("Result of command %s : %s", $data, $result));
            }
        }
        return $result;
    }
}
