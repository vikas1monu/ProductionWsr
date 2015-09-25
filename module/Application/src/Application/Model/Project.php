<?php
 namespace Application\Model;
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

 class Project implements InputFilterAwareInterface
{
     public $id;
     public $quality;
     public $schedule;
     public $process;
     public $effortVariance;
     public $customerManagment;
     public $currentMonthBillingTarget;
     protected $inputFilter; 
     
     public function exchangeArray($data)
     {
         $this->id     = (isset($data['project_name']))     ? $data['project_name']     : 0;
         $this->quality = (isset($data['quality'])) ? $data['quality'] : 0;
         $this->schedule  = (isset($data['schedule']))  ? $data['schedule']  : 0;
         $this->process  = (isset($data['process']))  ? $data['process']  : 0;
         $this->effortVariance  = (isset($data['effort_variance']))  ? $data['effort_variance']  : 0;
         $this->customerManagment  = (isset($data['customer_management']))  ? $data['customer_management']  : 0;
         $this->currentMonthBillingTarget  = (isset($data['current_month_billing_target']))  ? $data['current_month_billing_target']  : 0;
     }


      public function getArrayCopy()
     {
         return get_object_vars($this);
     }
     public function setInputFilter(InputFilterInterface $inputFilter)
     {
         throw new \Exception("Not used");
     }

     public function getInputFilter()
     {
         if (!$this->inputFilter) {
             $inputFilter = new InputFilter();

             $inputFilter->add(array(
                 'name'     => 'project_name',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'Int'),
                 ),
             ));

             $inputFilter->add(array(
                 'name' => 'quality',
                  'required' => true,
                  'validators' => array(
                 array(
                   'name' => 'Float',
        
                ),
            ),
             ));

              $inputFilter->add(array(
                 'name'     => 'schedule',
                 'required' => true,
                 'validators' => array(
                 array(
                   'name' => 'Float',
        
                ),
            ),
             ));

             $inputFilter->add(array(
                 'name'     => 'process',
                 'required' => true,
                 'validators' => array(
                 array(
                   'name' => 'Float',
        
                ),
            ),
             ));

             $inputFilter->add(array(
                 'name'     => 'effort_variance',
                 'required' => true,
                 'validators' => array(
                 array(
                   'name' => 'Float',
        
                ),
            ),
             ));
            $inputFilter->add(array(
                 'name'     => 'customer_management',
                 'required' => true,
                 'validators' => array(
                 array(
                   'name' => 'Float',
        
                ),
            ),
             ));
             $inputFilter->add(array(
                 'name'     => 'current_month_billing_target',
                 'required' => true,
                 'validators' => array(
                 array(
                   'name' => 'Float',
        
                ),
            ),
             ));
             $this->inputFilter = $inputFilter;
         }
          
         return $this->inputFilter;
     }
 }
