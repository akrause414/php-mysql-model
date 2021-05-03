<?php namespace HaloYa\Example\Model;

use HaloYa\Model;
use HaloYa\Example\TableGateway;

class Bar extends Model implements Model\NestedObjectMappings {

    /**
     * Sets this object as the return type when using fetchObject or similar methods
     */
    use Model\DatabaseConsumer;

    /**
     * String representation of a TableGateway class
     *
     * This ties the model to it's database table via TableGateway
     *
     * @var string
     */
    const tableGateway = TableGateway\Bar::class;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Foo
     */
    public $foo;

    /**
     * @inheritDoc
     */
    public function getNestedObjectsMappings() {
        return [
            'foo' => Foo::class
        ];
    }
}