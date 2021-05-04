<?php


namespace HaloYa\SQL\Component;


class Table
{

    /**
     * @var string
     */
    protected string $tableAlias;

    /**
     * @var string
     */
    protected string $tableName;

    /**
     * Table constructor
     *
     * @param string $name
     * @param string $alias
     */
    public function __construct(string $name, string $alias = '') {
        $this->tableName = $name;
        $this->tableAlias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias(): string {
        return $this->tableAlias;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function __toString(): string {
        return "{$this->getName()}" . (
            $this->getAlias() && $this->getAlias() !== $this->getName() ? " AS {$this->getAlias()}" : ""
        );
    }
}