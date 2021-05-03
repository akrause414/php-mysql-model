<?php namespace HaloYa\SQL;

use HaloYa\SQL\Join\Builder;
use HaloYa\TableGateway;
use Exception;

class Select {

    use WhereTrait;
    use GroupByTrait;
    use OrderByTrait;
    use LimitTrait;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * Associative array with keys as alias and values as database field names
     *
     * @var string[]
     */
    protected $fields = [];

    /**
     * @var Join[]
     */
    protected $joins = [];

    /**
     * Select constructor
     *
     * @param string $tableName
     * @param string[] $fields
     */
    public function __construct($tableName, $fields) {
        $this->tableName = $tableName;
        $this->fields = $fields;
    }

    /**
     * @param string $tableAliasName
     * @param array $condition
     * @return Select
     * @throws Exception
     */
    public function Join($tableAliasName, $condition) {
        $this->joins[] = Builder::getJoin($tableAliasName, $condition);
        return $this;
    }

    private function getJoins() {
        $joins = [];
        if ($this->joins) {
            foreach ($this->joins as $join) {
                $joins[] = (string)$join;
            }
        }
        return implode(' ', $joins);
    }

    /**
     * @return string
     */
    private function getReturnFields() {
        $fields = [];
        if ($this->fields) {
            foreach ($this->fields as $alias => $dbField) {
                if (!strpos($dbField, '.')) {
                    $dbField = "t.$dbField";
                }
                $field = Helper::escapeField($dbField);
                if ($alias && is_string($alias) && $alias != $dbField) {
                    $field .= ' AS ' . Helper::escapeField($alias);
                }
                $fields[] = $field;
            }
        }
        else {
            $fields[] = '*';
        }
        return implode(', ', $fields);
    }

    /**
     * @param TableGateway $tableGateway
     * @return Select
     */
    public function compile($tableGateway) {
        if ($this->where) {
            $this->where->compile($tableGateway);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getParams() {
        if ($this->where) {
            return $this->where->getParams();
        }
        return [];
    }

    /**
     * @return string
     */
    public function __toString() {
        return implode(
            ' ',
            array_filter([
                'SELECT',
                $this->getReturnFields(),
                'FROM',
                "{$this->tableName} t",
                $this->getJoins(),
                (string)$this->where,
                (string)$this->limit
            ])
        );
    }
}