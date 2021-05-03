<?php namespace HaloYa\SQL\Where;

use HaloYa\SQL\Where\Condition\Builder;
use Exception;

class Condition {

    /**
     * @var string
     */
    protected $field;

    /**
     * @var Condition
     */
    protected $condition;

    /**
     * Condition constructor
     *
     * @param array $condition
     * @throws Exception
     */
    public function __construct($condition) {
        $this->field = array_keys($condition)[0];
        $this->condition = Builder::getCondition($condition);
    }

    /**
     * @return string
     */
    public function getField() {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperator() {
        return $this->condition->getOperator();
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->condition->getValue();
    }

    /**
     * @return string
     */
    public function __toString() {
        return "`{$this->field}` " . (string)$this->condition;
    }
}