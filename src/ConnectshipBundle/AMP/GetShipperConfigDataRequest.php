<?php

namespace ConnectshipBundle\AMP;

class GetShipperConfigDataRequest {

    /**
     * @var string $carrier
     */
    protected $carrier = null;

    /**
     * @var string $shipper
     */
    protected $shipper = null;

    /**
     * @var string $preProcess
     */
    protected $preProcess = null;

    /**
     * @var string $postProcess
     */
    protected $postProcess = null;

    /**
     * @var language $locale
     */
    protected $locale = null;

    /**
     * @var string $asyncCorrelationData
     */
    protected $asyncCorrelationData = null;

    /**
     * @param string $carrier
     * @param string $shipper
     * @param string $preProcess
     * @param string $postProcess
     * @param language $locale
     * @param string $asyncCorrelationData
     */
    public function __construct($carrier, $shipper, $preProcess, $postProcess, $locale, $asyncCorrelationData) {
        $this->carrier = $carrier;
        $this->shipper = $shipper;
        $this->preProcess = $preProcess;
        $this->postProcess = $postProcess;
        $this->locale = $locale;
        $this->asyncCorrelationData = $asyncCorrelationData;
    }

    /**
     * @return string
     */
    public function getCarrier() {
        return $this->carrier;
    }

    /**
     * @param string $carrier
     * @return \ConnectshipBundle\AMP\GetShipperConfigDataRequest
     */
    public function setCarrier($carrier) {
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * @return string
     */
    public function getShipper() {
        return $this->shipper;
    }

    /**
     * @param string $shipper
     * @return \ConnectshipBundle\AMP\GetShipperConfigDataRequest
     */
    public function setShipper($shipper) {
        $this->shipper = $shipper;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreProcess() {
        return $this->preProcess;
    }

    /**
     * @param string $preProcess
     * @return \ConnectshipBundle\AMP\GetShipperConfigDataRequest
     */
    public function setPreProcess($preProcess) {
        $this->preProcess = $preProcess;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostProcess() {
        return $this->postProcess;
    }

    /**
     * @param string $postProcess
     * @return \ConnectshipBundle\AMP\GetShipperConfigDataRequest
     */
    public function setPostProcess($postProcess) {
        $this->postProcess = $postProcess;
        return $this;
    }

    /**
     * @return language
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * @param language $locale
     * @return \ConnectshipBundle\AMP\GetShipperConfigDataRequest
     */
    public function setLocale($locale) {
        $this->locale = $locale;
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
     * @return \ConnectshipBundle\AMP\GetShipperConfigDataRequest
     */
    public function setAsyncCorrelationData($asyncCorrelationData) {
        $this->asyncCorrelationData = $asyncCorrelationData;
        return $this;
    }

}
