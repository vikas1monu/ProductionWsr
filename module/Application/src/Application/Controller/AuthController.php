<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Model\PUser;
use Application\Model\PUserTable;



class AuthController extends AbstractActionController
{
    /*
    This is loginAction called when 
    a valid user login 
    otherwise give an error.
    */
    public function loginAction() 
    {   
        $loginForm = new LoginForm();
        $request = $this->getServiceLocator()->get('request');
        $data = $request->getPost()->toArray();
        $loginForm->setData($data);
        if ($request->isPost()) {
            if ($loginForm->isValid()) {
                 $this->validateForm($data, $loginForm);
            }
        }
        return new ViewModel(array('form' => $loginForm));
    }

    /* this function validate login form data
       with valid user email id and password
    */ 
    public function validateForm($data, $loginForm) 
    { 
        $user = new PUser();
        $userTable = $this->getServiceLocator()->get('PUserTable');
        $user = $userTable->getOne(array('p_user_email_id' => $data['usr_email_id']));
        if (!empty($user) && !empty($data['usr_password'])) 
        {
            if ($data['usr_password']==$user->getPassword())
            {  
                $this->redirect()->toRoute('home', array('action','index'));
            }
            
        }     
    }
}
   
