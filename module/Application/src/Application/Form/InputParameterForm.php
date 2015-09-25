<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

class InputParameterForm extends Form
{
     protected $adapter;
    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->adapter =$dbAdapter;
        parent::__construct('inputParameter');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('class', 'c-form clearfix');
         
        $this->add(array(
          'type' => 'Zend\Form\Element\Select',
          'name' => 'project_name',
          'attributes' => array(
                'class' => 'form-control'
            ),
          'options' => array(
          'empty_option' => 'Please select an project',
          'value_options' => $this->getOptionsForSelect(),
          )
         ));

        $this->add(array(
            'name' => 'quality',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'Placeholder' => 'Enter Quality Value'
            ),
            
        ));

        $this->add(array(
            'name' => 'schedule',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'Placeholder' => 'Enter Schedule Value'
            ),
        ));

        $this->add(array(
            'name' => 'process',
			'type' => 'text', 
            'attributes' => array(
               'class' => 'form-control',
                'Placeholder' => 'Enter Process Value'
            ),
        ));	

        $this->add(array(
            'name' => 'effort_variance',
            'type' => 'text', 
            'attributes' => array(
               'class' => 'form-control',
                'Placeholder' => 'Enter Effort Variance Value'
            ),
        ));

        $this->add(array(
            'name' => 'customer_management',
            'type' => 'text', 
            'attributes' => array(
                'class' => 'form-control',
                'Placeholder' => 'Enter CM Value'
            ),
        )); 
         $this->add(array(
            'name' => 'current_month_billing_target',
            'type' => 'text', 
            'attributes' => array(
                'class' => 'form-control',
                'Placeholder' => 'Enter CMB Target'
            ),
        )); 
        $this->add(array(
            'name' => 'review_date',
            'type' => 'text', 
            'attributes' => array(
                'class' => 'form-control',
                'Placeholder' => 'Enter Review Date'
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
    }
    // getOptionsForSelect used for fetch the project_name from database
    public function getOptionsForSelect()
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT r_project_id,r_project_name  FROM r_project';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['r_project_id']] = $res['r_project_name'];
        }
        return $selectData;
    }

}