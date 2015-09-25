<?php


namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Project;
use Application\Model\ProjectTable;
use Application\Model\PUser;
use Application\Model\PUserTable;
use Application\Form\InputParameterForm;
use tcpdf; 
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{   
   /*this is default indexAction called 
   when indexController hit.
   */
    public function indexAction()
    {
       //create an object of ProjectTable for accessing the methods into it.
      $project = $this->getServiceLocator()->get('ProjectTable');
      //call the getwsr() method that defined in the ProjectTable.php
      $data = $project->getWsr();
      foreach ($data as $key => $project)
      { 
        //calculation for fields part over here
        //and add more elements into the array 
        $data[$key]['percentRemain'] = 100-$project['percentComplete'];
        $data[$key]['currentMnthBillTarget'] = (($project['effort'])+($project['estimate']-$project['spent'])+(($project['totalEstimate']/($project['diffDate']+1))*($project['endDay']-$project['startDay'])));
        $data[$key]['hrsTillLastMnth'] = ($project['totalConsumeHrs']-$project['totalCurrentMnthConsumeHrs']);
        $data[$key]['hrsNeedToFinish'] = ($project['estimatedHours']-$project['totalCurrentMnthConsumeHrs']);
      
      }
      
      
         //pass the data array to view 
        return new ViewModel(array(
             'projects' =>$data,
         ));
    }
    
    /*
    this action generate pdf from html content 
    */
     public function PdfAction()
     {    
      $pdf = new TCPDF('L');
      $pdf->SetPrintHeader(true);
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
            }

        // set font
        $pdf->SetFont('helvetica', '', 8);


        // add a page
        $pdf->AddPage();
        
        //get report data into $data variable
        $project = $this->getServiceLocator()->get('ProjectTable');
        $data = $project->getWsr();
        
        $view = new PhpRenderer();
        $resolver = new TemplateMapResolver();
        //set the path of the pdf.phtml file
        $resolver->setMap(array(
            'PDFTemplate' => '/var/www/html/WSRAutomation/module/Application/view/application/index/pdf.phtml'
        ));
        $view->setResolver($resolver);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('PDFTemplate')
                ->setVariables(array(
                    'projects' =>$data,
                    'view' =>'pdf'
        ));
        $html = $view->render($viewModel);
        $pdf->writeHTML($html, true, 0,true, 0);
        $pdf->Output('WsrReport.pdf', 'I');

    }
 

    /*
    this addAction for add input quality parameters
    */ 
    public function addAction()
     {
     // get the projectTable methods after making an object.
      $projectTable = $this->getServiceLocator()->get('ProjectTable');
      //make the connection with db for drop down list
      $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
       //object of input parameter form
       $form = new InputParameterForm($dbAdapter);
       //you can also set label for button 
        $form->get('submit')->setValue('Add');
        //get http request either POST or GET
        $request = $this->getRequest();
        //check if request is POST
         if ($request->isPost()) 
         {   //create an object for Project 
             $project = new Project();
             //check the validations and filters
             $form->setInputFilter($project->getInputFilter());
             //set the form data.
             $form->setData($request->getPost());
             //check your form valid or not using isvalid function
             if ($form->isValid()) {
              $a= $form->getData();
                //if form is valid then get input data and passed to process
                 $project->exchangeArray($form->getData());
                 //then save the data value into db 
                 $projectTable->saveProject($project);
          }
            // Redirect to WSR report 
             $this->redirect()->toRoute('home');
        }
           //shortcut method for pass form array to view file.
          return array('form' => $form);
      }
}
