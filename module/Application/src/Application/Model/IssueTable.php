<?php
namespace Application\Model;


 class IssueTable
 {   
     const TABLE_NAME = 'r_issue';
     
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

}

 
