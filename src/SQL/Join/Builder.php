<?php namespace HaloYa\SQL\Join;

use HaloYa\SQL\Component\Field;
use HaloYa\SQL\Component\Table;
use HaloYa\SQL\Join\Condition;
use HaloYa\SQL\Join;
use Exception;
use HaloYa\TableGateway;

class Builder
{

    const JOINS_CLASS = [
        self::JOIN_LEFT => Left::class,
        self::JOIN_RIGHT => Right::class
    ];

    const JOIN_LEFT = 'LEFT';
    const JOIN_RIGHT = 'RIGHT';
    const JOIN_INNER = 'INNER';
    const JOIN_FULL = 'FULL';

    /**
     * @param string $joinTableAliasName
     * @param array $conditions
     * @return Join
     * @throws Exception
     */
    public static function getJoin(string $joinTableAliasName, array $conditions): Join
    {
        //TODO: accept join types in conditions array
        $join = self::JOIN_LEFT;
        foreach ($conditions as $field => $condition) {
            foreach ($condition as $operator => $joinField) {
                return self::buildJoin(
                    $join,
                    self::buildTable($joinField, $joinTableAliasName),
                    self::buildOn($field, $operator, $joinField, $joinTableAliasName),
                );
            }
        }
        throw new Exception('Invalid join configuration');
    }

    /**
     * @param mixed $field
     * @return string
     */
    private static function getTableString($field): string
    {
        if (
            $field instanceof Field &&
            $tableName = $field->getTableName()
        ) {
            return $tableName;
        }
        if (self::isTableFieldString($field)) {
            return explode('.', $field)[0];
        }
        return '';
    }

    /**
     * @param mixed $field
     * @return string
     */
    private static function getFieldString($field): string
    {
        if (
            $field instanceof Field &&
            $name = $field->getName()
        ) {
            return $field->getName();
        }
        if (self::isTableFieldString($field)) {
            return explode('.', $field)[1];
        }
        return is_string($field) ? $field : '';
    }

    /**
     * @param string $tableField
     * @return bool
     */
    private static function isTableFieldString(string $tableField): bool
    {
        if (
            is_string($tableField) &&
            strpos($tableField, '.')
        ) {
            return count(explode('.', $tableField)) === 2;
        }
        return false;
    }

    /**
     * @param mixed $joinName
     * @return string
     */
    private static function getJoinName($joinName): string
    {
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
     * @param Table $table
     * @param Condition\On $on
     * @return Join|null
     * @throws Exception
     */
    private static function buildJoin(string $join, Table $table, Condition\On $on): ?Join
    {
        if ($join = self::getJoinName($join)) {
            $joinClass = self::JOINS_CLASS[$join];
            return new $joinClass($table, $on);
        } else {
            throw new Exception("Invalid join: " . $join);
        }
    }

    /**
     * @param mixed $field
     * @param mixed $operator
     * @param mixed $joinField
     * @param string $joinTableAliasName
     * @return Condition\On
     * @throws Exception
     */
    private static function buildOn($field, $operator, $joinField, string $joinTableAliasName): Condition\On {
        return new Condition\On(
            self::buildField($field, TableGateway::tableAlias),
            [
                $operator => self::buildField($joinField, $joinTableAliasName)
            ]
        );
    }

    /**
     * @param mixed $field
     * @param string $alias
     * @return Field
     * @throws Exception
     */
    private static function buildField($field, string $alias = ''): Field
    {
        if (!$fieldString = self::getFieldString($field)) {
            throw new Exception('Invalid join field');
        }
        return new Field(
            $fieldString,
            $alias ?: self::getTableString($field)
        );
    }

    /**
     * @param mixed $field
     * @param string $alias
     * @return Table
     * @throws Exception
     */
    private static function buildTable($field, string $alias): Table
    {
        if (!$table = self::getTableString($field)) {
            throw new Exception('Invalid join table');
        }
        return new Table(
            $table,
            $alias
        );
    }
}