<?php

namespace App\Service;

class CustomMessageService
{

    /*public function __toString()
    {
        return "Hello I'm a Service !";
    }*/

    public function __construct(){

        $this->message="Hello I'm a Service !";
    }

    public function getMessage(){

        return $this->message;
    }


}