<?php namespace HaloYa\Tests;

use HaloYa\Example\Model\Bar;
use HaloYa\Example\Model\Foo;
use HaloYa\TableGateway;
use HaloYa\Tests\Mock;
use PHPUnit\Framework\TestCase;
use Exception;

class TableGatewayTest extends TestCase {

    /**
     * @var Mock\TestDatabaseConnector
     */
    public static Mock\TestDatabaseConnector $db;

    public static function setUpBeforeClass():void {
        parent::setUpBeforeClass();
        TableGateway::setDatabaseConnector(self::$db = new Mock\TestDatabaseConnector());
    }

    public function testSetDatabaseConnector() {
        $this->expectException(Exception::class);
        TableGateway::setDatabaseConnector(
            'nonsense'
        );
    }

    public function testFetchObject() {
        Foo::fetchObject(1);
        $this->assertEquals(
            'SELECT t.`key` AS `id`, t.`value` AS `name` FROM foo t WHERE `key` =:w0',
            self::$db->getLastSql()
        );
    }

    public function testFetchObjectNested() {
        self::$db->setMockResponse(
            $this->getBarMockResponse()
        );
        $response = Bar::fetchObject(1);
        $this->assertEquals(
            'SELECT t.`id` AS `id`, t.`name` AS `name`, foo.`key` AS `foo_id`, foo.`value` AS `foo_name` FROM bar t LEFT JOIN foo foo ON t.foo_id = foo.`key` WHERE `id` =:w0',
            self::$db->getLastSql()
        );
        $this->assertEquals($this->getBarMock(), $response);
    }

    private function getBarMockResponse():Bar {
        $mock = new Bar();
        $mock->id = 1;
        $mock->name = 'Far';
        $mock->foo_id = 1;
        $mock->foo_name = 'Too';
        return $mock;
    }

    private function getBarMock():Bar {
        $mock = new Bar();
        $mock->id = 1;
        $mock->name = 'Far';
        $mock->foo = $this->getFooMock();
        return $mock;
    }

    private function getFooMock():Foo {
        $mock = new Foo();
        $mock->id = 1;
        $mock->name = 'Too';
        return $mock;
    }
}