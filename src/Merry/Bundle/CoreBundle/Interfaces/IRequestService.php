<?php
namespace Merry\Bundle\CoreBundle\Interfaces;

interface IRequestService {
    /*
     * Envoi une requete a un server
     * @param string $cfgHost ip host
     * @param int $cfgHost port host
     * @param mixed $data params to send
     * @return $mixed result of $request;
     */
    public function query($cfgHost, $cfgPort, $data=null);  
    
}
