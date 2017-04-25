<?php

namespace ConnectshipBundle\AMP;

class VoidPackageResultList
{

    /**
     * @var VoidPackageResult[] $item
     */
    protected $item = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return VoidPackageResult[]
     */
    public function getItem()
    {
      return $this->item;
    }

    /**
     * @param VoidPackageResult[] $item
     * @return \ConnectshipBundle\AMP\VoidPackageResultList
     */
    public function setItem(array $item = null)
    {
      $this->item = $item;
      return $this;
    }

}
