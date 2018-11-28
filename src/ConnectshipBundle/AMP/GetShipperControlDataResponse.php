<?php

namespace ConnectshipBundle\AMP;

class GetShipperControlDataResponse {

    /**
     * @var DictionaryResult $result
     */
    protected $result = null;

    /**
     * @var string $asyncCorrelationData
     */
    protected $asyncCorrelationData = null;

    /**
     * @param DictionaryResult $result
     * @param string $asyncCorrelationData
     */
    public function __construct($result, $asyncCorrelationData) {
        $this->result = $result;
        $this->asyncCorrelationData = $asyncCorrelationData;
    }

    /**
     * @return DictionaryResult
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * @param DictionaryResult $result
     * @return \ConnectshipBundle\AMP\GetShipperControlDataResponse
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
     * @return \ConnectshipBundle\AMP\GetShipperControlDataResponse
     */
    public function setAsyncCorrelationData($asyncCorrelationData) {
        $this->asyncCorrelationData = $asyncCorrelationData;
        return $this;
    }

}
