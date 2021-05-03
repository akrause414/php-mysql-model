<?php namespace HaloYa\SQL;

use HaloYa\SQL\Where\Condition;
use HaloYa\SQL\Where\Condition\Between;
use HaloYa\SQL\Where\Condition\NotBetween;
use HaloYa\TableGateway;
use Exception;

class Where {

    /**
     * @var string
     */
    const BIND_PREFIX = 'w';

    /**
     * @var Condition[]
     */
    protected $conditions;

    /**
     * @var array
     */
    protected $binds;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var bool
     */
    protected $hasCompiled;

    /**
     * Where constructor
     *
     * @param array $conditions
     * @throws Exception
     */
    public function __construct($conditions) {
        $this->setConditions($conditions);
    }

    /**
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param array $conditions
     * @throws Exception
     */
    public function setConditions($conditions) {
        foreach ($conditions as $dbField => $condition) {
            $this->addCondition($dbField, $condition);
        }
    }

    /**
     * @param TableGateway $tableGateway
     */
    public function compile($tableGateway) {
        if (!$this->hasCompiled) {
            $this->binds = [];
            $this->params = [];
            if ($this->conditions) {
                foreach ($this->conditions as $condition) {
                    $value = $tableGateway->validateField(
                        $condition->getField(),
                        $tableGateway->filterField(
                            $condition->getField(),
                            $condition->getValue()
                        )
                    );
                    if (is_array($value)) {
                        if (
                        in_array(
                            $condition->getOperator(),
                            [
                                Between::OPERATOR,
                                NotBetween::OPERATOR
                            ]
                        )
                        ) {
                            $this->binds[] = str_replace(
                                [
                                    Between::FIRST_BIND,
                                    Between::SECOND_BIND
                                ],
                                [
                                    $this->bindParam($value[0]),
                                    $this->bindParam($value[1])
                                ],
                                (string)$condition
                            );
                        }
                        else {
                            $this->binds[] = str_replace(
                                Condition\Condition::BIND_NAME,
                                Helper::escape($value),
                                (string)$condition
                            );
                        }
                    }
                    else {
                        $this->binds[] = str_replace(
                            Condition\Condition::BIND_NAME,
                            $this->bindParam($value),
                            (string)$condition
                        );
                    }
                }
            }
            $this->hasCompiled = true;
        }
    }

    /**
     * @param string $dbField
     * @param array $condition
     * @return Where
     * @throws Exception
     */
    private function addCondition($dbField, $condition) {
        $this->conditions[] = new Condition([$dbField => $condition]);
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
        if ($this->binds) {
            return 'WHERE ' . implode(' AND ', $this->binds);
        }
        return '';
    }
}