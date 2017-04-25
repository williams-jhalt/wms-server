<?php

namespace ConnectshipBundle\AMP;

class HolidayDictionary
{

    /**
     * @var HolidayItem[] $item
     */
    protected $item = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return HolidayItem[]
     */
    public function getItem()
    {
      return $this->item;
    }

    /**
     * @param HolidayItem[] $item
     * @return \ConnectshipBundle\AMP\HolidayDictionary
     */
    public function setItem(array $item = null)
    {
      $this->item = $item;
      return $this;
    }

}
