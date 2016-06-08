<?php

namespace Merry\Bundle\CoreBundle\Services;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Merry\Bundle\CoreBundle\Interfaces\IRequestService;

class HttpBasicRequestService implements IRequestService {
    
    private $logger;
    
    public function __construct(LoggerInterface $logger)
    {
       $this->logger = $logger;
    }
    
    public function query($cfgHost, $cfgPort, $data= null) 
    {    
        if(!isset($data['path']) || !isset($data['method']))
        {
            $this->logger->error(sprintf("Error : data argument(s) missing."));
            return null;
        }
        
        $url= 'http://'.$cfgHost.':'.$cfgPort.$data['path'];
        $options = array(
            CURLOPT_URL            => $url,   
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => $data['method']=='POST'
        );
        
        if(!empty($data['username'])&&!empty($data['password']))
        {
            $options[CURLOPT_HTTPAUTH]= CURLAUTH_DIGEST;
            $options[CURLOPT_USERPWD]= $data['username'] . ":" . $data['password'];

        }
        
        if(isset($data['values']) && $data['method']=='POST')
        {
            $options[CURLOPT_POSTFIELDS]= http_build_query($data['values']);
        }
        
        $ch = curl_init();
        curl_setopt_array( $ch, $options );
        try {
          $this->logger->info(sprintf("Request : %s ", $url));
          $raw_response  = curl_exec( $ch );

          // validate CURL status
          if(curl_errno($ch))
              throw new \Exception(curl_error($ch), 500);

          // validate HTTP status code (user/password credential issues)
          $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          if ($status_code != 200)
              throw new \Exception("Response with Status Code [" . $status_code . "].", 500);
          
          if ($ch != null) curl_close($ch);
          return $raw_response; 
        } catch(\Exception $e) {
            if ($ch != null) curl_close($ch);
            $this->logger->error(sprintf("Error http request : %s\n%s", $e->getCode(),$e->getMessage()));
            return null;
        }       
    }

}
