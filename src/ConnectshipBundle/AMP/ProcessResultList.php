<?php

namespace ConnectshipBundle\AMP;

class ProcessResultList
{

    /**
     * @var ProcessResult[] $item
     */
    protected $item = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return ProcessResult[]
     */
    public function getItem()
    {
      return $this->item;
    }

    /**
     * @param ProcessResult[] $item
     * @return \ConnectshipBundle\AMP\ProcessResultList
     */
    public function setItem(array $item = null)
    {
      $this->item = $item;
      return $this;
    }

}
