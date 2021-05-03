<?php namespace HaloYa\SQL;

trait GroupByTrait {

    /**
     * @var GroupBy
     */
    protected $groupBy;

    /**
     * @param string|string[] $field
     * @return $this
     */
    public function groupBy($field) {
        $this->groupBy = new GroupBy(
            Helper::getStringArray($field)
        );
        return $this;
    }
}