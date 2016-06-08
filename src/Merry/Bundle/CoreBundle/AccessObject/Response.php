<?php
namespace Merry\Bundle\CoreBundle\AccessObject;

use Merry\Bundle\CoreBundle\Constants\HttpStatusCodes;
use Symfony\Component\HttpFoundation\JsonResponse;

class Response extends JsonResponse{
     /** @var int*/
    public $code;
    
    /** @var string*/
    public $message;
    
    /** @var mixed*/
    public $result;
    
    public function setSuccess($result=null)
    {
        $this->code= HttpStatusCodes::SUCCESS;
        $this->result= $result;
        $this->updateData();
        return $this;
    }
    
    public function updateData(){
        $this->setData(array('code'=>$this->code,
                             'message'=>$this->message,
                             'result'=>  $this->result));
    }
}
