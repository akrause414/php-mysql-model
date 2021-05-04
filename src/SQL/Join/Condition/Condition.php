<?php


namespace HaloYa\SQL\Join\Condition;


class Condition
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected string $operator;

    /**
     * Equal constructor
     *
     * @param mixed $value
     * @param string $operator
     */
    public function __construct($value, string $operator)
    {
        $this->value = $value;
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
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
        return "{$this->getOperator()} " . (string)$this->value;
    }
}