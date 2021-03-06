<?php namespace HaloYa\SQL\Where\Condition;

class Condition
{

    const BIND_NAME = 'value_bind';

    const OPERATOR = '';

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Equal constructor
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return static::OPERATOR;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::OPERATOR . static::BIND_NAME;
    }
}