<?php

namespace ConnectshipBundle\AMP;

class TransmitItemResultList {

    /**
     * @var TransmitItemResult[] $item
     */
    protected $item = null;

    public function __construct() {
        
    }

    /**
     * @return TransmitItemResult[]
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param TransmitItemResult[] $item
     * @return \ConnectshipBundle\AMP\TransmitItemResultList
     */
    public function setItem(array $item = null) {
        $this->item = $item;
        return $this;
    }

}
