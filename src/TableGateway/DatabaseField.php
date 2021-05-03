<?php namespace HaloYa\TableGateway;

class DatabaseField {

    /**
     * @var string
     */
    protected $name;

    protected $filters;

    protected $validators;

    public function __construct($field, $options = []) {
        $this->name = $field;
    }

}