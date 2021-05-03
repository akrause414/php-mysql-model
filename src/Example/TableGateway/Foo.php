<?php namespace HaloYa\Example\TableGateway;

use HaloYa\TableGateway;

class Foo extends TableGateway {

    /**
     * The name of the table in the database
     *
     * @var string
     */
    const tableName = 'foo';

    /**
     * When fetching single objects from the database this field will be used in WHERE statements
     *  Ex: SELECT key as id, value as name FROM foo WHERE key = 1;
     *
     * This should be the name of the primary key column/field in the database
     *
     * @var string
     */
    const primaryKey = 'key';

    /**
     * Maps the actual database columns/fields to the properties/fields in the model
     *
     * In this example the model properties are mapped:
     *  'id' that ties to the database column/field named 'key'
     *  'name' that ties to the database column/field named 'value'
     *
     * @var array
     */
    const fieldMappings = [
        'id' => 'key',
        'name' => 'value'
    ];
}