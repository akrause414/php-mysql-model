<?php namespace HaloYa\SQL;

use HaloYa\TableGateway;
use Exception;

class Helper {

    /**
     * @param string $prefix
     * @param int $index
     * @return string
     */
    public static function getBindName($prefix, $index) {
        return "$prefix$index";
    }

    /**
     * @param mixed $value
     * @return string
     * @throws Exception
     */
    public static function escape($value) {
        if (is_array($value)) {
            return implode(
                ',',
                array_filter(
                    $value,
                    function ($v) {
                        return Helper::escape($v);
                    }
                )
            );
        }
        else if (preg_match('/^[0-9]+$/', $value)) {
            return $value;
        }
        $db = TableGateway::getDatabaseConnector();
        return $db::escape($value);
    }

    /**
     * @param string $fieldName
     * @return string
     */
    public static function escapeField($fieldName) {
        $parts = explode('.', $fieldName);
        if (count($parts) === 2) {
            return "{$parts[0]}.`{$parts[1]}`";
        }
        return "`$fieldName`";
    }

    /**
     * @param string|string[] $stringOrStrings
     * @return string[]
     */
    public static function getStringArray($stringOrStrings) {
        if (is_string($stringOrStrings)) {
            return [$stringOrStrings];
        }
        return $stringOrStrings;
    }
}