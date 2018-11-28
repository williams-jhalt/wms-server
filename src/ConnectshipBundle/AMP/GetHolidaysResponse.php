<?php

namespace ConnectshipBundle\AMP;

class GetHolidaysResponse {

    /**
     * @var ListHolidaysResult $result
     */
    protected $result = null;

    /**
     * @var string $asyncCorrelationData
     */
    protected $asyncCorrelationData = null;

    /**
     * @param ListHolidaysResult $result
     * @param string $asyncCorrelationData
     */
    public function __construct($result, $asyncCorrelationData) {
        $this->result = $result;
        $this->asyncCorrelationData = $asyncCorrelationData;
    }

    /**
     * @return ListHolidaysResult
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * @param ListHolidaysResult $result
     * @return \ConnectshipBundle\AMP\GetHolidaysResponse
     */
    public function setResult($result) {
        $this->result = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getAsyncCorrelationData() {
        return $this->asyncCorrelationData;
    }

    /**
     * @param string $asyncCorrelationData
     * @return \ConnectshipBundle\AMP\GetHolidaysResponse
     */
    public function setAsyncCorrelationData($asyncCorrelationData) {
        $this->asyncCorrelationData = $asyncCorrelationData;
        return $this;
    }

}
