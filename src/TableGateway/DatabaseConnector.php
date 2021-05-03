<?php namespace HaloYa\TableGateway;

use HaloYa\Model;

interface DatabaseConnector {

    /**
     * @param string $value
     * @return string
     */
    public static function escape($value);

    /**
     * @param string $sql
     * @param array $params
     */
    public static function execute($sql, $params);

    /**
     * @param string $sql
     * @param array $params
     * @param string $class
     * @return Model
     */
    public static function getRow($sql, $params, $class);
}