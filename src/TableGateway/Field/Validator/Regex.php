<?php namespace HaloYa\TableGateway\Field\Validator;

use HaloYa\TableGateway\Field\ValidatorInterface;
use Exception;

class Regex implements ValidatorInterface {

    /**
     * @var string
     */
    protected $pattern;

    /**
     * Regex constructor
     *
     * @param $pattern
     */
    public function __construct($pattern) {
        $this->setPattern($pattern);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value) {
        if (is_array($value)) {
            foreach ($value as $v) {
                if (!preg_match($this->pattern, $v)) {
                    return false;
                }
            }
            return true;
        }
        return (bool)preg_match($this->pattern, $value);
    }

    /**
     * @param mixed $value
     * @throws Exception
     */
    public function validate($value) {
        if (!$this->isValid($value)) {
            throw new Exception('Regex validation failed against pattern: ' . $this->pattern);
        }
    }

    /**
     * @param string $pattern
     */
    private function setPattern($pattern) {
        $this->pattern = "@^$pattern$@";
    }
}