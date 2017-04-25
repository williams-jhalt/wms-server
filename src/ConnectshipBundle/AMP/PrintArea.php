<?php

namespace ConnectshipBundle\AMP;

class PrintArea
{

    /**
     * @var int $width
     */
    protected $width = null;

    /**
     * @var int $height
     */
    protected $height = null;

    /**
     * @var int $horizontalOffset
     */
    protected $horizontalOffset = null;

    /**
     * @var int $verticalOffset
     */
    protected $verticalOffset = null;

    /**
     * @var int $orientation
     */
    protected $orientation = null;

    /**
     * @var boolean $frontFace
     */
    protected $frontFace = null;

    /**
     * @var boolean $backFace
     */
    protected $backFace = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return int
     */
    public function getWidth()
    {
      return $this->width;
    }

    /**
     * @param int $width
     * @return \ConnectshipBundle\AMP\PrintArea
     */
    public function setWidth($width)
    {
      $this->width = $width;
      return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
      return $this->height;
    }

    /**
     * @param int $height
     * @return \ConnectshipBundle\AMP\PrintArea
     */
    public function setHeight($height)
    {
      $this->height = $height;
      return $this;
    }

    /**
     * @return int
     */
    public function getHorizontalOffset()
    {
      return $this->horizontalOffset;
    }

    /**
     * @param int $horizontalOffset
     * @return \ConnectshipBundle\AMP\PrintArea
     */
    public function setHorizontalOffset($horizontalOffset)
    {
      $this->horizontalOffset = $horizontalOffset;
      return $this;
    }

    /**
     * @return int
     */
    public function getVerticalOffset()
    {
      return $this->verticalOffset;
    }

    /**
     * @param int $verticalOffset
     * @return \ConnectshipBundle\AMP\PrintArea
     */
    public function setVerticalOffset($verticalOffset)
    {
      $this->verticalOffset = $verticalOffset;
      return $this;
    }

    /**
     * @return int
     */
    public function getOrientation()
    {
      return $this->orientation;
    }

    /**
     * @param int $orientation
     * @return \ConnectshipBundle\AMP\PrintArea
     */
    public function setOrientation($orientation)
    {
      $this->orientation = $orientation;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getFrontFace()
    {
      return $this->frontFace;
    }

    /**
     * @param boolean $frontFace
     * @return \ConnectshipBundle\AMP\PrintArea
     */
    public function setFrontFace($frontFace)
    {
      $this->frontFace = $frontFace;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getBackFace()
    {
      return $this->backFace;
    }

    /**
     * @param boolean $backFace
     * @return \ConnectshipBundle\AMP\PrintArea
     */
    public function setBackFace($backFace)
    {
      $this->backFace = $backFace;
      return $this;
    }

}
