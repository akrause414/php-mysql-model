<?php namespace HaloYa\SQL;

use Exception;

class Join {

    /**
     * @var string
     */
    const type = 'LEFT';

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $tableAliasName;

    /**
     * @var array
     */
    protected $condition;

    /**
     * Join constructor
     *
     * @param string|string[] $tableName
     * @param array $condition
     * @throws Exception
     */
    public function __construct($tableName, $condition) {
        if (
            is_array($tableName) &&
            count($tableName) === 1
        ) {
            $this->tableAliasName = (string)array_keys($tableName)[0];
            $this->tableName = (string)array_pop($tableName);
        }
        else if (is_string($tableName)) {
            $this->tableName = $tableName;
        }
        else {
            throw new Exception('Invalid table');
        }
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function __toString() {
        $field = array_keys($this->condition)[0];
        $operator = array_keys($this->condition[$field])[0];
        $joinField = Helper::escapeField($this->condition[$field][$operator]);
        return static::type . " JOIN {$this->tableName} {$this->tableAliasName} ON t.$field $operator " . str_replace($this->tableName, $this->tableAliasName, $joinField);
    }
}