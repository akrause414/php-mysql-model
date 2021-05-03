<?php namespace HaloYa\SQL;

use HaloYa\TableGateway;

class Update {

    use WhereTrait;
    use LimitTrait;

    /**
     * @var string
     */
    const BIND_PREFIX = 'u';

    /**
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * @var array
     */
    protected $binds;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $data;

    /**
     * Select constructor
     *
     * @param TableGateway $tableGateway
     * @param array $data
     */
    public function __construct($tableGateway, $data) {
        $this->tableGateway = $tableGateway;
        $this->data = $this->tableGateway->getMappedData($data);
    }

    /**
     * @return array
     */
    public function getParams() {
        $this->compile();
        return array_merge(
            $this->where->getParams()
        );
    }

    /**
     * @return string
     */
    private function getBinds() {
        $values = [];
        foreach ($this->data as $dbField => $value) {
            $values[] = "`$dbField`=:" . Helper::getBindName(self::BIND_PREFIX, count($values));
        }
        return implode(', ', $values);
    }

    /**
     * @return $this
     */
    private function compile() {
        $this->binds = [];
        if ($this->where) {
            $this->where->compile($this->tableGateway);
        }
        foreach ($this->data as $dbField => $value) {
            $this->binds[] = "`$dbField`=:" . $this->bindParam($value);
        }
        return $this;
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function bindParam($value) {
        $key = self::BIND_PREFIX . count($this->params);
        $this->params[$key] = Helper::escape($value);
        return $key;
    }

    /**
     * @return string
     */
    public function __toString() {
        $this->compile();
        if (
            ($values = $this->getBinds()) &&
            $where = (string)$this->where
        ) {
            return "UPDATE {$this->tableGateway->getTableName()} SET $values $where";
        }
        return '';
    }
}