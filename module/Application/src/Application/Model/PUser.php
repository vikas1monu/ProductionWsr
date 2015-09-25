<?php

namespace Application\Model;

use Application\Model\AbstractDbModel;

class PUser extends AbstractDbModel

 {
     protected $pUserEmailId;
     protected $pUserPassword;
     protected $pUserId;

    public function getId() 
    {
        return $this->pUserId;
    }
     public function getEmail() 
     {
        return $this->pUserEmailId;
    }

     public function getPassword() 
     {
        return $this->pUserPassword;
    }
}