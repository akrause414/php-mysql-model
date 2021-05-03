<?php namespace HaloYa\SQL\Where\Condition;

use HaloYa\SQL\Helper;

class In extends Condition {

    /**
     * @var string
     */
    const OPERATOR = 'IN';

    /**
     * @return string
     */
    public function __toString() {
        return static::OPERATOR . '(:' . static::BIND_NAME . ')';
    }
}