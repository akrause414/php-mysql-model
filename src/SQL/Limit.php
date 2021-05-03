<?php namespace HaloYa\SQL;

class Limit {

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * Limit constructor
     *
     * @param int $limit
     * @param int $offset
     */
    public function __construct($limit = 1, $offset = 0) {
        $this->limit = (int)$limit;
        $this->offset = (int)$offset;
    }

    /**
     * @return string
     */
    public function __toString() {
        if ($this->limit) {
            if ($this->offset) {
                return "LIMIT {$this->offset},{$this->limit}";
            }
            return "LIMIT {$this->limit}";
        }
        return '';
    }
}