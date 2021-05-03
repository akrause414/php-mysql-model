<?php namespace HaloYa\TableGateway\Field\Filter;

use HaloYa\TableGateway\Field\FilterInterface;

class Regex implements FilterInterface {

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
     * @param string $value
     * @return string|string[]|null
     */
    public function filter($value) {
        return preg_replace($this->pattern, '', $value);
    }

    /**
     * @param string $pattern
     */
    private function setPattern($pattern) {
        $this->pattern = $pattern;
    }
}