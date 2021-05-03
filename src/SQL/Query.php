<?php namespace HaloYa\SQL;

use HaloYa\TableGateway;
use Exception;

class Query {

    /**
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * @var Select
     */
    protected $select;

    /**
     * Select constructor
     *
     * @param TableGateway $tableGateway
     * @throws Exception
     */
    public function __construct($tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->select = new Select(
            $tableGateway->getTableName(),
            $tableGateway->getSelectFields()
        );
    }

    /**
     * @param array $conditions
     * @return Query
     * @throws Exception
     */
    public function where($conditions) {
        $this->select->where($conditions);
        return $this;
    }

    /**
     * @param array $conditions
     * @return Query
     */
    public function groupBy($conditions) {
        $this->select->groupBy($conditions);
        return $this;
    }

    /**
     * @param array $conditions
     * @return Query
     */
    public function orderBy($conditions) {
        $this->select->orderBy($conditions);
        return $this;
    }

    /**
     * @param int $limit
     * @return Query
     */
    public function limit($limit) {
        $this->select->limit($limit);
        return $this;
    }

    /**
     * @param int $offset
     * @return Query
     */
    public function offset($offset) {
        $this->select->offset($offset);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        return '';
    }
}