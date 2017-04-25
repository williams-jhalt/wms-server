<?php

namespace ConnectshipBundle\AMP;

class StockDescriptorList
{

    /**
     * @var StockDescriptor[] $item
     */
    protected $item = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return StockDescriptor[]
     */
    public function getItem()
    {
      return $this->item;
    }

    /**
     * @param StockDescriptor[] $item
     * @return \ConnectshipBundle\AMP\StockDescriptorList
     */
    public function setItem(array $item = null)
    {
      $this->item = $item;
      return $this;
    }

}
