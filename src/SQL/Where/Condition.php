<?php namespace HaloYa\SQL\Where;

use HaloYa\SQL\Where\Condition\Builder;
use Exception;

class Condition
{

    /**
     * @var string
     */
    protected string $table;

    /**
     * @var string
     */
    protected string $field;

    /**
     * @var Condition\Condition
     */
    protected Condition\Condition $condition;

    /**
     * Condition constructor
     *
     * @param string $field
     * @param array $condition
     * @throws Exception
     */
    public function __construct(string $field, array $condition)
    {
        $this->setField($field);
        $this->condition = Builder::getCondition($condition);
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->condition->getOperator();
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->condition->getValue();
    }

    /**
     * @param string $field
     * @return $this
     */
    private function setField(string $field): Condition
    {
        if (strpos($field, '.')) {
            $parts = explode('.', $field);
            $this->table = $parts[0];
            $this->field = $parts[1];
        }
        else {
            $this->field = $field;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (!empty($this->table) ? "{$this->table}." : "") . "`{$this->field}` " . (string)$this->condition;
    }
}