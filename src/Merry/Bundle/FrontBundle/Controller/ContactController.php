<?php

namespace Merry\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    public function sendAction()
    {
        $admin_email = "joan.francois@merryservices.net";
        $from_name   = "joan.francois@merryservices.net";
        
        $request = $this->get('request');
        $data = $request->request->all();
        
        if(isset($data['subscriberemail'])) {
             $subject 	= "Email Suscriber Information";
             $message	= "Email Address: ".$data['subscriberemail'];
        }else{
            $user_name      = strip_tags($data['username']);
            $user_email     = strip_tags($data['useremail']);
            $comment_text   = strip_tags($data['commenttext']);
            $subject        = "New Contact Information";
            $message        = "Name: $user_name <br/>";
            $message        .= "Email: $user_email <br/>";
            $message        .= "Comment: $comment_text <br/>";
        }
        
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from_name)
            ->setTo($admin_email)
            ->setBody($message);
        
        if($this->get('mailer')->send($message)){
            return new Response("1");
        }
        return new Response("2");
    }
}
