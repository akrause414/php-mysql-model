<?php namespace HaloYa\SQL\Join\Condition;

use HaloYa\SQL\Component\Field;
use Exception;

class On
{

    /**
     * @var Field
     */
    protected Field $field;

    /**
     * @var Condition
     */
    protected Condition $condition;

    /**
     * Condition constructor
     *
     * @param Field $field
     * @param array $condition
     * @throws Exception
     */
    public function __construct(Field $field, array $condition)
    {
        $this->field = $field;
        $this->condition = Builder::getCondition($condition);
    }

    public function __toString(): string
    {
        return implode(
            ' ',
            [
                'ON',
                (string)$this->field,
                (string)$this->condition
            ]
        );
    }
}