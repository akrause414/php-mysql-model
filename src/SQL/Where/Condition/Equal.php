<?php namespace HaloYa\SQL\Where\Condition;

class Equal extends Condition {

    /**
     * @var string
     */
    const OPERATOR = '=';

    /**
     * @return string
     */
    public function __toString() {
        return static::OPERATOR . ':' . static::BIND_NAME;
    }
}