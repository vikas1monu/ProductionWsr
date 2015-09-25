<?php

namespace Application\Model;

use Zend\Filter\Word\CamelCaseToUnderscore;

abstract class AbstractDbModel
{

    public $tableFields;
    public $tableName;

    public function __construct($data = null)
    {
        if (empty($data)) {
            $data = array();
        }
        $this->exchangeArray($data);
    }

    public function getCleanObject()
    {
        unset($this->tableFields);
        unset($this->tableName);
        return $this;
    }

    public function getRawData()
    {
        $tableFields = $this->getTableFields();
        $data = array();
        foreach ($tableFields as $objKey => $field) {
            if (isset($this->{$objKey})) {
                $data[$field] = $this->{$objKey};
            }
        }
        return $data;
    }

    public function exchangeArray($data)
    {
        $tableFileds = $this->getTableFields();
        foreach ($tableFileds as $objKey => $field) {
            if (isset($data[$field])) {
                $this->{$objKey} = $data[$field];
            }
        }
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getTableFields()
    {
        if (empty($this->tableFields)) {
            $this->setTableFields();
        }
        return $this->tableFields;
    }

    public function setTableFields()
    {
        $tableFields = array();
        $tmpArray = $this->getArrayCopy();
        $camelCaseToUnderscore = new CamelCaseToUnderscore();
        foreach ($tmpArray as $objKey => $value) {
            if (!in_array($objKey, array('tableFields'))) {
                $tableFields[$objKey] = strtolower($camelCaseToUnderscore->filter($objKey));
            }
        }
        $this->tableFields = $tableFields;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

}
