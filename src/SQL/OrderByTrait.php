<?php namespace HaloYa\SQL;

trait OrderByTrait {

    /**
     * @var OrderBy
     */
    protected $orderBy;

    /**
     * @param string|string[] $field
     * @return $this
     */
    public function orderAsc($field) {
        return $this->orderBy(
            Helper::getStringArray($field),
            OrderBy::ASC
        );
    }

    /**
     * @param string|string[] $field
     * @return $this
     */
    public function orderDesc($field) {
        return $this->orderBy(
            Helper::getStringArray($field),
            OrderBy::DESC
        );
    }

    /**
     * @param string|string[] $fields
     * @param string $order
     * @return $this
     */
    private function orderBy($fields, $order = null) {
        if (is_null($this->orderBy)) {
            $this->orderBy = new OrderBy(
                Helper::getStringArray($fields),
                $order
            );
        }
        else {
            $this->orderBy->addOrderBy(
                Helper::getStringArray($fields),
                $order
            );
        }
        return $this;
    }
}