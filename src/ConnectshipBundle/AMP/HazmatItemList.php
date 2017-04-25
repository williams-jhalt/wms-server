<?php

namespace ConnectshipBundle\AMP;

class HazmatItemList
{

    /**
     * @var HazmatItem[] $item
     */
    protected $item = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return HazmatItem[]
     */
    public function getItem()
    {
      return $this->item;
    }

    /**
     * @param HazmatItem[] $item
     * @return \ConnectshipBundle\AMP\HazmatItemList
     */
    public function setItem(array $item = null)
    {
      $this->item = $item;
      return $this;
    }

}
