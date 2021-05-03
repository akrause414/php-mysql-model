<?php namespace HaloYa\Tests\Mock;

use HaloYa\Model;
use HaloYa\TableGateway\DatabaseConnector;

class TestDatabaseConnector implements DatabaseConnector {

    /**
     * @var string
     */
    protected static $lastSql;

    /**
     * @var mixed
     */
    protected static $mockResponse;

    /**
     * @inheritDoc
     */
    public static function getRow($sql, $params, $class) {
        static::$lastSql = $sql;
        return self::$mockResponse instanceof Model
            ? self::$mockResponse
            : new Model();
    }

    /**
     * @inheritDoc
     */
    public static function escape($value) {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public static function execute($sql, $params) {
        static::$lastSql = $sql;
    }

    /**
     * @return string
     */
    public function getLastSql() {
        return static::$lastSql;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setMockResponse($data) {
        self::$mockResponse = $data;
        return $this;
    }
}