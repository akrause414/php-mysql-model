<?php namespace HaloYa\SQL;

trait LimitTrait {

    /**
     * @var Limit
     */
    protected $limit;

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit($limit, $offset = 0) {
        $this->limit = new Limit($limit, $offset);
        return $this;
    }
}