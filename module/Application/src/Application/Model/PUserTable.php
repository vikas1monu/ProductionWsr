<?php

namespace Application\Model;

use Application\Model\AbstractDbTable;
use Application\Model\PUser;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate;

class PUserTable extends AbstractDbTable {

    const TABLE_NAME = 'p_user_detail';

    public function getOne($where = array()) {
      $row = $this->tableGateway->select($where)->current();
        if (!$row) {
            return null;
        }
        return $row;
    }

     public function getUser($email)
     {
        
         $rowset = $this->tableGateway->select(array('p_user_email_id' => $email));
         
         $row = $rowset->current();
         
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }


    public function getMany($where = array(), $params = array()) {
        $select = new Select(self::TABLE_NAME);
        $select->where($where);
        $select->order("p_user_id DESC");
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $select->{$key}($value);
            }
        }
        return $this->tableGateway->selectWith($select);
    }

    public function save(PUser $user) {
        $data = $user->getRawData();
        if ($user->getId() > 0) {
            $id = $user->getId();
            $this->tableGateway->update($data, array('p_user_id' => $id));
        } else {
            if (!$this->tableGateway->insert($data)) {
                throw new \Exception("Could not new row $id");
            }
            $id = (int) $this->tableGateway->lastInsertValue;
        }
        return $this->getOne(array('p_user_id' => $id));
    }

    
    
     
}
