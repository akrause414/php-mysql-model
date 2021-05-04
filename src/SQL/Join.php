<?php namespace HaloYa\SQL;

use HaloYa\SQL\Component\Table;
use HaloYa\SQL\Join\Condition\On;

class Join {

    /**
     * @var string
     */
    const type = 'LEFT';

    /**
     * @var Table
     */
    protected Table $table;

    /**
     * @var On
     */
    protected On $on;

    /**
     * Join constructor
     *
     * @param Table $table
     * @param On $on
     */
    public function __construct(Table $table, On $on) {
        $this->table = $table;
        $this->on = $on;
    }

    /**
     * @return string
     */
    public function __toString() {
        return implode(
            ' ',
            [
                static::type,
                'JOIN',
                (string)$this->table,
                (string)$this->on
            ]
        );
    }
}