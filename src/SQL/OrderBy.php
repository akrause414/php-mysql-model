<?php namespace HaloYa\SQL;

class OrderBy {

    const ASC = 'ASC';
    const DESC = 'DESC';

    /**
     * @var array
     */
    protected $orders;

    /**
     * OrderBy constructor
     * @param string[] $fields
     * @param string $order
     */
    public function __construct($fields, $order = null) {
        $this->addOrderBy($fields, $order);
    }

    /**
     * @param string[] $fields
     * @param string $order
     */
    public function addOrderBy($fields, $order = null) {
        foreach ($fields as $field) {
            $this->orders[$field] = $this->getOrder($order);
        }
    }

    /**
     * @param string $str
     * @return string
     */
    private function getOrder($str) {
        if (
            is_string($str) &&
            strtoupper($str) === self::DESC
        ) {
            return self::DESC;
        }
        return self::ASC;
    }

    /**
     * @return string
     */
    public function __toString() {
        $orderBy = '';
        if ($this->orders) {
            $lastOrder = null;
            $orders = [];
            foreach ($this->orders as $dbField => $order) {
                if (is_null($lastOrder) || $order === $lastOrder) {
                    $orders[] = $dbField;
                }
                else {
                    $orderBy .= implode(', ', $orders) . " $lastOrder ";
                    $orders = [$dbField];
                }
                $lastOrder = $order;
            }
            $orderBy .= implode(', ', $orders) . " $lastOrder";
            return "ORDER BY $orderBy";
        }
        return $orderBy;
    }
}