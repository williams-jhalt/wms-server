<?php

namespace ConnectshipBundle\AMP;

class ListPaymentTypesResponse
{

    /**
     * @var IdentityListResult $result
     */
    protected $result = null;

    /**
     * @var string $asyncCorrelationData
     */
    protected $asyncCorrelationData = null;

    /**
     * @param IdentityListResult $result
     * @param string $asyncCorrelationData
     */
    public function __construct($result, $asyncCorrelationData)
    {
      $this->result = $result;
      $this->asyncCorrelationData = $asyncCorrelationData;
    }

    /**
     * @return IdentityListResult
     */
    public function getResult()
    {
      return $this->result;
    }

    /**
     * @param IdentityListResult $result
     * @return \ConnectshipBundle\AMP\ListPaymentTypesResponse
     */
    public function setResult($result)
    {
      $this->result = $result;
      return $this;
    }

    /**
     * @return string
     */
    public function getAsyncCorrelationData()
    {
      return $this->asyncCorrelationData;
    }

    /**
     * @param string $asyncCorrelationData
     * @return \ConnectshipBundle\AMP\ListPaymentTypesResponse
     */
    public function setAsyncCorrelationData($asyncCorrelationData)
    {
      $this->asyncCorrelationData = $asyncCorrelationData;
      return $this;
    }

}
