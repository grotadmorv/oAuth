<?php

namespace Controller;

class DefaultController
{
    public function homeAction()
    {
        echo "TEST";
    }

    function  authTokenAction() {
        $bytes = random_bytes(255);
        $token = bin2hex($bytes);
    
        $res = array('auth_token'=>$token);
        //TODO insert token into DB
        return json_encode($res);
    }
}
