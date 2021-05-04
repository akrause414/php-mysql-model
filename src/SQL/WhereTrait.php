<?php namespace HaloYa\SQL;

use Exception;

trait WhereTrait
{

    /**
     * @var Where
     */
    protected Where $where;

    /**
     * @param array $conditions
     * @return $this
     * @throws Exception
     */
    public function where(array $conditions)
    {
        if (empty($this->where)) {
            $this->where = new Where($conditions);
        } else {
            $this->where->setConditions($conditions);
        }
        return $this;
    }
}