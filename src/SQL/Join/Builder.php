<?php namespace HaloYa\SQL\Join;

use HaloYa\SQL\Where\Condition;
use HaloYa\SQL\Join;
use Exception;

class Builder {

    const JOINS_CLASS = [
        self::JOIN_LEFT => Left::class,
        self::JOIN_RIGHT => Right::class
    ];

    const JOIN_LEFT = 'LEFT';
    const JOIN_RIGHT = 'RIGHT';
    const JOIN_INNER = 'INNER';
    const JOIN_FULL = 'FULL';

    /**
     * @param string $tableAliasName
     * @param array $conditions
     * @return Join
     * @throws Exception
     */
    public static function getJoin($tableAliasName, $conditions) {
        foreach ($conditions as $field => $condition) {
            if (!$join = self::getJoinName($field)) {
                $join = self::JOIN_LEFT;
            }
            if (self::isField($field)) {
                foreach ($condition as $operator => $joinField) {
                    if (
                        Condition\Builder::getOperator($operator) &&
                        $table = self::getTable($joinField)
                    ) {
                        return self::buildJoin($join, [$tableAliasName => $table], [$field => [$operator => $joinField]]);
                    }
                }
            }
        }
        throw new Exception('Invalid join configuration');
    }

    /**
     * @param mixed $field
     * @return bool
     */
    private static function isField($field) {
        return is_string($field) && !self::isTableField($field);
    }

    /**
     * @param string $tableField
     * @return bool
     */
    private static function getTable($tableField) {
        if (self::isTableField($tableField)) {
            return explode('.', $tableField)[0];
        }
        return false;
    }

    /**
     * @param string $tableField
     * @return bool
     */
    private static function isTableField($tableField) {
        if (
            is_string($tableField) &&
            strpos($tableField, '.')
        ) {
            $parts = explode('.', $tableField);
            return count($parts) === 2;
        }
        return false;
    }

    /**
     * @param mixed $joinName
     * @return string
     */
    private static function getJoinName($joinName) {
        if (is_string($joinName)) {
            $joinName = strtoupper($joinName);
            if (!empty(self::JOINS_CLASS[$joinName])) {
                return $joinName;
            }
        }
        return '';
    }

    /**
     * @param string $join
     * @param array $tableName
     * @param array $on
     * @return Join|null
     * @throws Exception
     */
    private static function buildJoin($join, $tableName, $on) {
        if ($join = self::getJoinName($join)) {
            $joinClass = self::JOINS_CLASS[$join];
            return new $joinClass($tableName, $on);
        }
        else {
            throw new Exception("Invalid join");
        }
    }
}