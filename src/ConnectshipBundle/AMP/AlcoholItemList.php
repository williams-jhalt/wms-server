<?php

namespace ConnectshipBundle\AMP;

class AlcoholItemList
{

    /**
     * @var AlcoholItem[] $item
     */
    protected $item = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return AlcoholItem[]
     */
    public function getItem()
    {
      return $this->item;
    }

    /**
     * @param AlcoholItem[] $item
     * @return \ConnectshipBundle\AMP\AlcoholItemList
     */
    public function setItem(array $item = null)
    {
      $this->item = $item;
      return $this;
    }

}
