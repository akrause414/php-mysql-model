<?php namespace HaloYa\SQL\Where\Condition;

use Exception;

class Builder
{

    const OPERATORS_CLASS = [
        self::OPERATOR_EQUAL_TO => Equal::class,
        self::OPERATOR_NOT_EQUAL_TO => NotEqual::class,
        self::OPERATOR_IN => In::class,
        self::OPERATOR_NOT_IN => NotIn::class,
        self::OPERATOR_BETWEEN => Between::class,
        self::OPERATOR_NOT_BETWEEN => NotBetween::class
    ];

    const OPERATOR_EQUAL_TO = '=';
    const OPERATOR_NOT_EQUAL_TO = '!=';
    const OPERATOR_IN = 'IN';
    const OPERATOR_NOT_IN = 'NOT IN';
    const OPERATOR_BETWEEN = 'BETWEEN';
    const OPERATOR_NOT_BETWEEN = 'NOT BETWEEN';

    /**
     * @param array $condition
     * @return Condition
     * @throws Exception
     */
    public static function getCondition(array $condition): Condition
    {
        foreach ($condition as $operator => $value) {
            return self::buildCondition($operator, $value);
        }
        throw new Exception('Unexpected condition');
    }

    /**
     * @param mixed $operator
     * @return string
     */
    public static function getOperator($operator): string
    {
        if (is_string($operator)) {
            $operator = strtoupper($operator);
            if (!empty(self::OPERATORS_CLASS[$operator])) {
                return $operator;
            }
        }
        return '';
    }

    /**
     * @param mixed $operator
     * @param mixed $value
     * @return Condition|null
     * @throws Exception
     */
    private static function buildCondition($operator, $value): ?Condition
    {
        if (
            $operator &&
            $operator = self::getOperator($operator)
        ) {
            $class = self::OPERATORS_CLASS[$operator];
            return new $class($value);
        }
        else if (is_string($operator)) {
            throw new Exception("Unknown operator: $operator");
        }
        else {
            return new Equal($value);
        }
    }
}