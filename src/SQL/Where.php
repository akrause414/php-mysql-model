<?php namespace HaloYa\SQL;

use HaloYa\SQL\Where\Condition;
use HaloYa\SQL\Where\Condition\Between;
use HaloYa\SQL\Where\Condition\NotBetween;
use HaloYa\TableGateway;
use Exception;

class Where
{

    /**
     * @var string
     */
    const BIND_PREFIX = 'w';

    /**
     * @var Condition[]
     */
    protected array $conditions;

    /**
     * @var array
     */
    protected array $binds;

    /**
     * @var array
     */
    protected array $params;

    /**
     * @var bool
     */
    protected bool $hasCompiled;

    /**
     * Where constructor
     *
     * @param array $conditions
     * @throws Exception
     */
    public function __construct(array $conditions)
    {
        $this->setConditions($conditions);
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $conditions
     * @throws Exception
     */
    public function setConditions(array $conditions)
    {
        foreach ($conditions as $dbField => $condition) {
            $this->addCondition($dbField, $condition);
        }
    }

    /**
     * @param TableGateway $tableGateway
     * @throws Exception
     */
    public function compile(TableGateway $tableGateway)
    {
        if (empty($this->hasCompiled)) {
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
                        } else {
                            $this->binds[] = str_replace(
                                Condition\Condition::BIND_NAME,
                                Helper::escape($value),
                                (string)$condition
                            );
                        }
                    } else {
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
    private function addCondition(string $dbField, array $condition): Where
    {
        $this->conditions[] = new Condition($dbField, $condition);
        return $this;
    }

    /**
     * @param mixed $value
     * @return string
     * @throws Exception
     */
    private function bindParam($value): string
    {
        $key = self::BIND_PREFIX . count($this->params);
        $this->params[$key] = Helper::escape($value);
        return $key;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->binds) {
            return 'WHERE ' . implode(' AND ', $this->binds);
        }
        return '';
    }
}