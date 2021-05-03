<?php namespace HaloYa\SQL;

class GroupBy {

    /**
     * @var array
     */
    protected $fields;

    /**
     * GroupBy constructor
     *
     * @param array $fields
     */
    public function __construct($fields) {
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function __toString() {
        if ($this->fields) {
            return 'GROUP BY ' . implode(', ', $this->fields);
        }
        return '';
    }
}