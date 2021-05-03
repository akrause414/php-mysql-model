<?php namespace HaloYa\TableGateway\Field\Filter\String;

use HaloYa\TableGateway\Field\FilterInterface;

class UpperCase implements FilterInterface {

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter($value) {
        if (is_array($value)) {
            return array_map(
                'strtoupper',
                $value
            );
        }
        return strtoupper($value);
    }
}