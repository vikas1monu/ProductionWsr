<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

abstract class AbstractDbTable
{

    /**
     * @var TableGateway
     */
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function selectWith(\Zend\Db\Sql\Select $select)
    {
        return $this->tableGateway->selectWith($select);
    }

    /**
     * @return string
     */
    protected function getCurrentDateString()
    {
        return \Application\Controller\AbstractAppController::getInstance()->getLocalTime();
    }

    /**
     * @return \DateTime
     */
    protected function getCurrentDateTime()
    {
        return new \DateTime("now", new \DateTimeZone(\Application\Controller\AbstractAppController::getInstance()->getLocalTimezone()));
    }

}
