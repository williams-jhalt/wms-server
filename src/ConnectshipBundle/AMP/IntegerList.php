<?php

namespace ConnectshipBundle\AMP;

class IntegerList
{

    /**
     * @var int[] $item
     */
    protected $item = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return int[]
     */
    public function getItem()
    {
      return $this->item;
    }

    /**
     * @param int[] $item
     * @return \ConnectshipBundle\AMP\IntegerList
     */
    public function setItem(array $item = null)
    {
      $this->item = $item;
      return $this;
    }

}
