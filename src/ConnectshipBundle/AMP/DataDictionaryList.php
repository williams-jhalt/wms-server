<?php

namespace ConnectshipBundle\AMP;

class DataDictionaryList {

    /**
     * @var DataDictionary[] $item
     */
    protected $item = null;

    public function __construct() {
        
    }

    /**
     * @return DataDictionary[]
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param DataDictionary[] $item
     * @return \ConnectshipBundle\AMP\DataDictionaryList
     */
    public function setItem(array $item = null) {
        $this->item = $item;
        return $this;
    }

}
