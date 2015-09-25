<?php


namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Sql;
 use Zend\Db\Adapter\Adapter;
 use Zend\Db\Sql\Select;
 use Zend\Server\Method\Prototype;
 use Zend\Db\Sql\Expression;

 class ProjectTable
 {
    const TABLE_NAME = 'r_project';
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

      public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }
     public function getProject($id)
     {
         $id  = (int) $id;

         $rowset = $this->tableGateway->select(array('r_project_id' => $id));
         
         $row = $rowset->current();
         
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }
     public function saveProject(Project $project)
     {
         $data = array(

             'r_project_quality' => $project->quality,
             'r_project_schedule'  => $project->schedule,
             'r_project_process' => $project->process,
             'r_project_effort_variance'  => $project->effortVariance,
             'r_project_customer_managment' => $project->customerManagment,
             'r_project_current_month_billing_target' =>$project->currentMonthBillingTarget,
         );

         $id = (int) $project->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getProject($id)) {
                 $this->tableGateway->update($data, array('r_project_id' => $id));
             } else {
                 throw new \Exception('Project id does not exist');
             }
         }
     }

    
      public function getWsr() {

        $dateStart = date('Y-m-01');
        $dateEnd =  date('Y-m-t');


        
        $select = new Select(self::TABLE_NAME);
        $columns = array(
            'projectName'     =>  'r_project_name',
             'quality'        =>  'r_project_quality',
             'schedule'       => 'r_project_schedule',
             'process'        => 'r_project_process',
             'effortVariance' => 'r_project_effort_variance',
             'customerManagment' => 'r_project_customer_managment',
             'currentMonthBillingTarget' => 'r_project_current_month_billing_target',
             'comment'        =>  'r_project_description',
        );
        $select->columns($columns);
        $subQry = new Select( TimeEntryTable::TABLE_NAME);
        $subQry->columns(array(  "projectId" => 'r_project_id',
            'consumeHrs'  => new Expression('SUM(r_issue_timelog_logged_hours)')));
             $subQry->group('r_project_id');

        $subQry2 = new Select( TimeEntryTable::TABLE_NAME);
        $subQry2->columns(array(  "projectID" => 'r_project_id',
            'currentMonthConsumeHrs'  => new Expression('SUM(r_issue_timelog_logged_hours)')));
             $subQry2->where->between('r_issue_timelog_date', $dateStart, $dateEnd);
             $subQry2->group('r_project_id');

        $subQry3 = new Select( IssueTable::TABLE_NAME);
        $subQry3->columns(array(  "sub3ID" => 'r_project_id',
            'effort'  => new Expression('SUM(r_issue_estimated_hours)')));
             $subQry3->where->between('r_issue_start_date', $dateStart, $dateEnd);
             $subQry3->where->between('r_issue_due_date', $dateStart, $dateEnd);
             $subQry3->where(array('r_issue_tracker_code' => 26)); 
             $subQry3->group('r_project_id');

        $subQry4 = new Select( IssueTable::TABLE_NAME);
        $subQry4->columns(array(  "sub4ID" => 'r_project_id',
            'estimate'  => new Expression('SUM(r_issue_estimated_hours)')
                   ));
             $subQry4->join(array(
            't' => TimeEntryTable::TABLE_NAME
                ), 't.r_issue_id=r_issue.r_issue_id', 
              array(
                'issueID' => 'r_issue_id',
                'spent'  => new Expression('SUM(r_issue_timelog_logged_hours)')),
                   Select::JOIN_LEFT);
        $subQry4->where(array('r_issue_tracker_code' => 26 ));
        $subQry4->where->between('r_issue_due_date', $dateStart, $dateEnd);
        //$subQry4->where->greaterThanOrEqualTo('r_issue_start_date',$dateStart);
        $subQry4->group('r_issue.r_project_id');
        
        $subQry5 = new Select( IssueTable::TABLE_NAME);
        $subQry5->columns(array(  "sub5ID" => 'r_project_id',
             'totalEstimate'  => new Expression('SUM(r_issue_estimated_hours)'),
            'start_day_of_project'  => new Expression('DAY(r_issue_start_date)'),
            'end_day_of_month'  => new Expression('DAY("2015-09-30")'),
        'diffrence_dates'  => new Expression('DATEDIFF(r_issue_due_date,r_issue_start_date)'),
            ));
        $subQry5->where->between('r_issue_start_date', $dateStart, $dateEnd);
        //$subQry5->where->greaterThan('r_issue_due_date',$dateEnd);
        $subQry5->where(array('r_issue_tracker_code' => 26)); 
        $subQry5->group('r_project_id');
        
        
        $select->join(array(
            'issue' => IssueTable::TABLE_NAME
                ), 'issue.r_project_id=r_project.r_project_id', 
              array(
              'owner' => new Expression('MAX(r_user_name)'),
              'estimatedHours' => new Expression('SUM(r_issue_estimated_hours)'),
              'percentComplete'  => 'r_issue_completion_ratio',
              'completionDate' => 'r_issue_target_completion_date',
              'projectType'  => 'r_project_type',
            ),Select::JOIN_LEFT);

         $select->join(array(
            'sub' => $subQry
            ), 'sub.projectId = r_project.r_project_id',
         array(
             'totalConsumeHrs' => 'consumeHrs',
            ),Select::JOIN_LEFT);

         $select->join(array(
            'sub2' => $subQry2
            ), 'sub2.projectID = r_project.r_project_id',
         array(
             'totalCurrentMnthConsumeHrs' => 'currentMonthConsumeHrs',
            ),Select::JOIN_LEFT);
    

         $select->join(array(
            'sub3' => $subQry3
            ), 'sub3.sub3ID = r_project.r_project_id',
         array(
             'effort' => 'effort',
            ),Select::JOIN_LEFT);

         $select->join(array(
            'sub4' => $subQry4
            ), 'sub4.sub4ID = r_project.r_project_id',
         array(
             'estimate' => 'estimate',
             'spent' => 'spent',

            ),Select::JOIN_LEFT);

         $select->join(array(
            'sub5' => $subQry5
            ), 'sub5.sub5ID = r_project.r_project_id',
         array(
             'totalEstimate' => 'totalEstimate',
             'startDay' => 'start_day_of_project',
             'endDay' => 'end_day_of_month',
             'diffDate' => 'diffrence_dates',
            ),Select::JOIN_LEFT);




          $select->where(array(
               'r_issue_tracker_code' => 26 
        ));
         $select->group('r_project_name','issue.r_user_name');
          
        $sql = new Sql($this->tableGateway->getAdapter());
        $db = $this->tableGateway->getAdapter()->getDriver()->getConnection()->getResource();

        return $this->getSqlContent($db, $sql, $select);
    }
    


      protected function getSqlContent($db, $sql, $select) 
      {
        $stmt = $db->query($sql->getSqlStringForSqlObject($select));
        //echo "<pre>";print_r($stmt);die;
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }



 }



