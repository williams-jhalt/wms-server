<?php

namespace ConnectshipBundle\AMP;

class DocumentResultList {

    /**
     * @var DocumentResult[] $item
     */
    protected $item = null;

    public function __construct() {
        
    }

    /**
     * @return DocumentResult[]
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param DocumentResult[] $item
     * @return \ConnectshipBundle\AMP\DocumentResultList
     */
    public function setItem(array $item = null) {
        $this->item = $item;
        return $this;
    }

}
