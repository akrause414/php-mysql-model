<?php namespace HaloYa;

use Exception;

class Model
{

    /**
     * @var string
     */
    const tableGateway = '';

    /**
     * @return TableGateway
     * @throws Exception
     */
    public static function tableGateway(): TableGateway
    {
        $className = get_called_class();
        return TableGateway::getGatewayInstance(static::tableGateway, new $className());
    }

    /**
     * @param string $fieldName
     * @param string|int $fieldValue
     * @return $this
     * @throws Exception
     */
    public static function fetchObjectByField(string $fieldName, $fieldValue)
    {
        return self::tableGateway()->fetchObjectByField($fieldName, $fieldValue);
    }

    /**
     * @param string|int $primaryKeyValue
     * @return $this
     * @throws Exception
     */
    public static function fetchObject($primaryKeyValue)
    {
        return self::tableGateway()->fetchObject($primaryKeyValue);
    }
}