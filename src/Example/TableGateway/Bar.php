<?php namespace HaloYa\Example\TableGateway;

use HaloYa\SQL\Component\Field;
use HaloYa\TableGateway;

class Bar extends TableGateway {

    /**
     * The name of the table in the database
     *
     * @var string
     */
    const tableName = 'bar';

    /**
     * When fetching single objects from the database this field will be used in WHERE statements
     *  Ex: SELECT key as id, value as name FROM foo WHERE key = 1;
     *
     * This should be the name of the primary key column/field in the database
     *
     * @var string
     */
    const primaryKey = 'id';

    /**
     * Maps the actual database columns/fields to the properties/fields in the model
     *
     * In this example the model properties are mapped:
     *  'id' that ties to the database column/field named 'id'
     *  'name' that ties to the database column/field named 'name'
     *
     * @var array
     */
    const fieldMappings = [
        'id' => 'id',
        'name' => 'name'
    ];

    /**
     * Maps properties in the model to join tables
     *
     * This is used in JOIN statements
     *  EX: LEFT JOIN foo ON foo_id = foo.key
     *
     * @return array
     */
    public function getJoinConditions(): array
    {
        return [
            'foo' => [
                'foo_id' => [
                    '=' => new Field(Foo::primaryKey, Foo::tableName)
                ]
            ]
        ];
    }
}