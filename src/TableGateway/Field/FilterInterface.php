<?php namespace HaloYa\TableGateway\Field;

interface FilterInterface {

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter($value);
}