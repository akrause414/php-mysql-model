<?php namespace HaloYa\SQL;

use Exception;

trait WhereTrait {

    /**
     * @var Where
     */
    protected $where;

    /**
     * @param array $conditions
     * @return $this
     * @throws Exception
     */
    public function where($conditions) {
        if (is_null($this->where)) {
            $this->where = new Where($conditions);
        }
        else {
            $this->where->setConditions($conditions);
        }
        return $this;
    }
}