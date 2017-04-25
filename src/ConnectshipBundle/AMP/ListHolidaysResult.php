<?php

namespace ConnectshipBundle\AMP;

class ListHolidaysResult
{

    /**
     * @var int $code
     */
    protected $code = null;

    /**
     * @var string $message
     */
    protected $message = null;

    /**
     * @var HolidayDictionary $resultData
     */
    protected $resultData = null;

    /**
     * @param int $code
     * @param string $message
     * @param HolidayDictionary $resultData
     */
    public function __construct($code, $message, $resultData)
    {
      $this->code = $code;
      $this->message = $message;
      $this->resultData = $resultData;
    }

    /**
     * @return int
     */
    public function getCode()
    {
      return $this->code;
    }

    /**
     * @param int $code
     * @return \ConnectshipBundle\AMP\ListHolidaysResult
     */
    public function setCode($code)
    {
      $this->code = $code;
      return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
      return $this->message;
    }

    /**
     * @param string $message
     * @return \ConnectshipBundle\AMP\ListHolidaysResult
     */
    public function setMessage($message)
    {
      $this->message = $message;
      return $this;
    }

    /**
     * @return HolidayDictionary
     */
    public function getResultData()
    {
      return $this->resultData;
    }

    /**
     * @param HolidayDictionary $resultData
     * @return \ConnectshipBundle\AMP\ListHolidaysResult
     */
    public function setResultData($resultData)
    {
      $this->resultData = $resultData;
      return $this;
    }

}
