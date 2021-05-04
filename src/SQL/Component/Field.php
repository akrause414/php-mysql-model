<?php


namespace HaloYa\SQL\Component;


class Field
{

    /**
     * @var string
     */
    protected string $fieldName;

    /**
     * @var string
     */
    protected string $tableName;

    /**
     * Field constructor
     *
     * @param string $field
     * @param string $table
     */
    public function __construct(string $field, string $table = '')
    {
        $this->fieldName = $field;
        $this->tableName = $table;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getTableName(): string {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return ($this->tableName ? "{$this->tableName}." : "") . "`{$this->fieldName}`";
    }
}