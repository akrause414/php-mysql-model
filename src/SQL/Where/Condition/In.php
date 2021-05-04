<?php namespace HaloYa\SQL\Where\Condition;

class In extends Condition {

    /**
     * @var string
     */
    const OPERATOR = 'IN';

    /**
     * @return string
     */
    public function __toString(): string {
        return static::OPERATOR . '(:' . static::BIND_NAME . ')';
    }
}