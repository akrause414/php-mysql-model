<?php namespace HaloYa;

use HaloYa\Model\NestedObjectMappings;
use HaloYa\SQL\Select;
use Exception;
use HaloYa\TableGateway\DatabaseConnector;

class TableGateway {

    /**
     * The name of the database table
     */
    const tableName = '';

    /**
     * The primary unique key to identify objects
     */
    const primaryKey = 'id';

    /**
     * Field mapping for alias names. Ex: ['alias' => 'name']
     */
    const fieldMappings = [];

    /**
     * Field mapping for joined tables
     */
    const joinMappings = [];

    /**
     * Database connection instance that implements DatabaseConnector interface
     *
     * @var DatabaseConnector
     */
    static private $dbConnector;

    /**
     * Database object container to cast data records to
     *
     * @var object
     */
    protected $object;

    /**
     * @param object $object
     */
    public function __construct($object = null) {
        $this->setObject($object);
    }

    /**
     * @return object
     */
    public function getObject() {
        return $this->object;
    }

    /**
     * @param $object
     * @return $this
     */
    protected function setObject($object) {
        $this->object = $object;
        return $this;
    }

    /**
     * @param DatabaseConnector $dbConnector
     * @throws Exception
     */
    public static function setDatabaseConnector($dbConnector) {
        if (!$dbConnector instanceof DatabaseConnector) {
            throw new Exception('Database connector class must implement DatabaseConnector interface');
        }
        self::$dbConnector = $dbConnector;
    }

    /**
     * @return DatabaseConnector
     * @throws Exception
     */
    public static function getDatabaseConnector() {
        if (empty(self::$dbConnector)) {
            throw new Exception('Database connector has not been set');
        }
        return self::$dbConnector;
    }

    /**
     * Returns the database table name
     *
     * @return string
     */
    public function getPrimaryKey() {
        return static::primaryKey;
    }

    /**
     * @return string
     */
    public function getTableName() {
        return static::tableName;
    }

    /**
     * @param string $fieldName
     * @param mixed $value
     * @return mixed
     */
    public function filterField($fieldName, $value) {
        $fieldName = $this->getFieldAlias($fieldName);
        $filters = $this->getFilters();
        if (isset($filters[$fieldName])) {
            foreach ($filters[$fieldName] as $filter) {
                $value = $filter->filter($value);
            }
        }
        return $value;
    }

    /**
     * @return array
     */
    public function getFilters() {
        return [];
    }

    /**
     * @param string $fieldName
     * @param mixed $value
     * @return mixed
     */
    public function validateField($fieldName, $value) {
        $fieldName = $this->getFieldAlias($fieldName);
        $validators = $this->getValidators();
        if (isset($validators[$fieldName])) {
            foreach ($validators[$fieldName] as $validator) {
                $validator->validate($value);
            }
        }
        return $value;
    }

    /**
     * @return array
     */
    public function getValidators() {
        return [];
    }

    /**
     * @param string $fieldName
     * @return string
     */
    public function getFieldAlias($fieldName) {
        return in_array($fieldName, static::fieldMappings) ? array_search($fieldName, static::fieldMappings) : $fieldName;
    }

    /**
     * Returns data using database field names
     *
     * @param array $data
     * @return array
     */
    public function getMappedData($data) {
        $mappedData = [];
        foreach ($data as $fieldName => $value) {
            $mappedData[$this->getMappedField($fieldName)] = $value;
        }
        return $mappedData;
    }

    /**
     * Check if this field has a mapped database field
     *
     * @param string $fieldAliasName
     * @return bool
     */
    public function hasMappedField($fieldAliasName) {
        return !empty(static::fieldMappings[$fieldAliasName]);
    }

    /**
     * Lookup database field name by alias
     *
     * @param string $fieldName
     * @return string
     */
    public function getMappedField($fieldName) {
        return $this->hasMappedField($fieldName) ? static::fieldMappings[$fieldName] : $fieldName;
    }

    /**
     * Check if this field has a mapped join conditions
     *
     * @param string $tableAliasName
     * @return bool
     */
    public function hasMappedJoinCondition($tableAliasName) {
        return !empty(static::joinMappings[$tableAliasName]);
    }

    /**
     * Lookup database join conditions by table alias name
     *
     * @param $tableAliasName
     * @return array
     */
    public function getMappedJoinCondition($tableAliasName) {
        if ($this->hasMappedJoinCondition($tableAliasName)) {
            return static::joinMappings[$tableAliasName];
        }
        return [];
    }

    /**
     * Get all mapped join conditions for this object
     *
     * @return array
     */
    public function getMappedJoinConditions() {
        $conditions = [];
        if (
            $this->object instanceof NestedObjectMappings
        ) {
            foreach ($this->object->getNestedObjectsMappings() as $tableAliasName => $objectClass) {
                if ($this->hasMappedJoinCondition($tableAliasName)) {
                    $conditions[$tableAliasName] = $this->getMappedJoinCondition($tableAliasName);
                }
            }
        }
        return $conditions;
    }

    /**
     * @param string $tableAliasName
     * @return object|null
     */
    public function getNestedJoinInstance($tableAliasName) {
        if ($this->object instanceof NestedObjectMappings) {
            if (!empty($class = $this->object->getNestedObjectsMappings()[$tableAliasName])) {
                return new $class();
            }
        }
        return null;
    }

    /**
     * Returns associative array with keys as alias names and values as database field names
     *
     * @return array
     * @throws Exception
     */
    public function getSelectFields() {
        $fields = [];
        $objFields = array_keys(
            get_object_vars(
                $this->object
            )
        );
        foreach ($objFields as $fieldAlias) {
            if (
                $joinFields = $this->getSelectJoinFields($fieldAlias)
            ) {
                $fields = array_merge($fields, $joinFields);
            }
            elseif ($this->hasMappedField($fieldAlias)) {
                $fields[$fieldAlias] = $this->getMappedField($fieldAlias);
            }
        }
        return $fields;
    }

    /**
     * @param string $tableAliasName
     * @return array
     * @throws Exception
     */
    public function getSelectJoinFields($tableAliasName) {
        $fields = [];
        if (
            $this->object instanceof NestedObjectMappings &&
            $this->hasMappedJoinCondition($tableAliasName)
        ) {
            if (!empty($nestedObjectClass = $this->object->getNestedObjectsMappings()[$tableAliasName])) {
                foreach (self::getInstanceGatewayInstance($nestedObjectClass)->getSelectFields() as $joinFieldAliasName => $dbFieldName) {
                    $fields[$tableAliasName . "_$joinFieldAliasName"] = "$tableAliasName.$dbFieldName";
                }
            }
        }
        return $fields;
    }

    /**
     * @param string|int $primaryKeyValue
     * @return Model
     * @throws Exception
     */
    public function fetchObject($primaryKeyValue) {
        return $this->fetchObjectByField(
            $this->getPrimaryKey(),
            $primaryKeyValue
        );
    }

    /**
     * @param string $field
     * @param string|int $value
     * @return Model
     * @throws Exception
     */
    public function fetchObjectByField($field, $value) {
        $select = new Select(
            $this->getTableName(),
            $this->getSelectFields()
        );
        foreach ($this->getMappedJoinConditions() as $tableAliasName => $joinCondition) {
            $select->Join($tableAliasName, $joinCondition);
        }
        $select->where([
            $this->getMappedField($field) => ['=' => $value]
        ])->compile($this);
        $db = self::getDatabaseConnector();
        return $this->transform(
            $db::getRow(
                (string)$select,
                $select->getParams(),
                get_class($this->getObject())
            )
        );
    }

    /*public function updateObject($object, $data) {
        $this->setObject($object);
        $key = $this->getPrimaryKey();
        if (!empty($keyValue = $this->object->$$key)) {
            $changedData = array_diff_assoc(
                $data,
                get_object_vars($this->object)
            );
            $update = new Update(
                $this,
                $this->getMappedData($changedData)
            );
            $update->where([
                $this->getPrimaryKey() => $keyValue
            ]);
            $db = self::getDatabaseConnector();
            $db::execute((string)$update, $update->getParams());
        }
    }

    public static function query($object, $cacheTtl = 0) {
        return new Query(
            self::getGatewayInstance(
                get_called_class(),
                $object,
                $cacheTtl
            )
        );
    }

    public static function update($data) {
        return new Update(
            self::getGatewayInstance(
                get_called_class()
            ),
            $data
        );
    }

    public static function insertObject($data) {
        $insertData = $this->getMappedData($data);
        sqlWrapper::insert(
            $this->getTableName()
            $insertData
        );
    }

    public static function replaceOject($obj) {
        $replaceData = $this->getMappedData(get_object_vars($obj));
        sqlWrapper::replaceInto(
            $this->getTableName
            $replaceData
        );
    }*/

    /**
     * Populates nested objects for mapped joins
     * Ex: $obj->property_property becomes $obj->property->property
     *
     * @param mixed $data
     * @return mixed
     */
    public function transform($data) {
        if (
            $data &&
            $this->object instanceof NestedObjectMappings
        ) {
            // Transform array result sets
            if (is_array($data)) {
                foreach ($data as $obj) {
                    $this->transform($obj);
                }
            }
            else {
                // Transform single result
                $this->transformObjectProperties($data);
            }
        }
        return $data;
    }

    /**
     * Populates nested objects and their properties recursively
     *
     * @param mixed $object
     * @return mixed
     */
    private function transformObjectProperties($object) {
        if (is_object($object)) {
            $nestedObjRef = null;
            $objectProperties = array_filter(
                array_keys(
                    get_object_vars($object)
                ),
                function ($key) {
                    return count(explode('_', $key)) >= 2;
                }
            );
            foreach ($objectProperties as $property) {
                $parts = explode('_', $property);
                $joinObjectAlias = $parts[0];
                // Build the field back together for recursive transformation
                $joinObjectField = implode('_', array_slice($parts, 1));
                // If we have the nested object set it for this objects property
                if (
                    !isset($object->$joinObjectAlias) &&
                    $obj = $this->getNestedJoinInstance($joinObjectAlias)
                ) {
                    $object->$joinObjectAlias = $obj;
                }
                // If the nested object for this objects property exists
                if (
                    isset($object->$joinObjectAlias) &&
                    is_object($object->$joinObjectAlias) &&
                    $nestedObjRef = &$object->$joinObjectAlias
                ) {
                    // Set the property in the nested object
                    $nestedObjRef->$joinObjectField = $object->$property;
                    // Remove the transformed property from the base object
                    unset($object->$property);
                }
            }
            // Get all the nested objects
            $nestedObjectsProperties = array_filter(
                array_keys(
                    get_object_vars($object)
                ),
                function ($key) use ($object) {
                    return is_object($object->$key);
                }
            );
            // Handle recursive transformation
            foreach ($nestedObjectsProperties as $property) {
                $this->transformObjectProperties($object->$property);
            }
        }
        return $object;
    }

    /**
     * @param string $gatewayClass
     * @param object $object
     * @param int $cacheTtl
     * @return TableGateway
     * @throws Exception
     */
    public static function getGatewayInstance($gatewayClass, $object = null, $cacheTtl = 0) {
        if (
            !(
                $gateway = new $gatewayClass($object, $cacheTtl)
            ) instanceof TableGateway
        ) {
            throw new Exception('Invalid TableGateway class: ' . (string)$gatewayClass);
        }
        return $gateway;
    }

    /**
     * @param string $objectClass
     * @return TableGateway
     * @throws Exception
     */
    public static function getInstanceGatewayInstance($objectClass) {
        if (
            !(
                $gateway = call_user_func([$objectClass, 'tableGateway'])
            ) instanceof TableGateway
        ) {
            throw new Exception('Invalid Model object class: ' . (string)$objectClass);
        }
        return $gateway;
    }
}