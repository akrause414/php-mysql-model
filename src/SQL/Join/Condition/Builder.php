<?php


namespace HaloYa\SQL\Join\Condition;

use HaloYa\SQL\Where;
use Exception;

class Builder
{

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
     * @param string $operator
     * @return string
     * @throws Exception
     */
    private static function getOperator(string $operator): string
    {
        if (
            !in_array(
                $op = Where\Condition\Builder::getOperator($operator),
                [
                    Where\Condition\Builder::OPERATOR_EQUAL_TO
                ]
            )
        ) {
            throw new Exception('Invalid operator: ' . $operator);
        }
        return $op;
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
            return new Condition($value, $operator);
        }
        else {
            return new Condition($value, Where\Condition\Builder::OPERATOR_EQUAL_TO);
        }
    }
}