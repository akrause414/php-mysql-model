<?php namespace HaloYa\TableGateway\Field\Filter\String;

use HaloYa\TableGateway\Field\FilterInterface;

class LowerCase implements FilterInterface {

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter($value) {
        if (is_array($value)) {
            return array_map(
                'strtolower',
                $value
            );
        }
        return strtolower($value);
    }
}