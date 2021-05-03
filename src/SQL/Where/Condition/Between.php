<?php namespace HaloYa\SQL\Where\Condition;

class Between extends Condition {

    const FIRST_BIND = 'this';

    const SECOND_BIND = 'that';

    const OPERATOR = 'BETWEEN';

    /**
     * @return string
     */
    public function __toString() {
        return static::OPERATOR . ' :' . self::FIRST_BIND . ' AND :' . self::SECOND_BIND;
    }
}