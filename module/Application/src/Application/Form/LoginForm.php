<?php
namespace Application\Form;
use Zend\Form\Form;
use Application\Validators\LogInInputFilter;

class LoginForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('login');
        $this->setAttribute('method', 'POST');
        $this->add(array(
            'name' => 'usr_email_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'Placeholder' => 'Enter Email'
            ),
           
        ));

        $this->add(array(
            'name' => 'usr_password',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control',
                'Placeholder' => 'Enter Password'
            ),
          
        ));
        $this->add(array(
            'name' => 'rememberme',
			'type' => 'checkbox', 
            'attributes' => array(
                'class' => 'checkbox',
            ),
            
        ));			
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Sumbit',
                'id' => 'submitbutton',
                'class' => 'btn btn-success pull-right'
            ),
        )); 
        $this->setInputFilter(new LogInInputFilter());
    }
}