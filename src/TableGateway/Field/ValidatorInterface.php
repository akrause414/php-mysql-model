<?php namespace HaloYa\TableGateway\Field;

use Exception;

interface ValidatorInterface {

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value);

    /**
     * @param mixed $value
     * @return void
     * @throws Exception
     */
    public function validate($value);
}