<?php

namespace LogicBrokerBundle\Adapter;

abstract class AbstractInventoryAdapter {
    
    /**
     * @var SplFileObject
     */
    private $file;
    
    public function __construct(SplFileObject $file) {
        $this->file = $file;
    }
    
    abstract public function writeHeader();
    abstract public function writeLine(Inventory $inventory);
    
}