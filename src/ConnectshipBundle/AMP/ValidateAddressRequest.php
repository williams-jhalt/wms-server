<?php

namespace ConnectshipBundle\AMP;

class ValidateAddressRequest {

    /**
     * @var NameAddress $address
     */
    protected $address = null;

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
     * @param NameAddress $address
     * @param string $preProcess
     * @param string $postProcess
     * @param language $locale
     * @param string $asyncCorrelationData
     */
    public function __construct($address, $preProcess, $postProcess, $locale, $asyncCorrelationData) {
        $this->address = $address;
        $this->preProcess = $preProcess;
        $this->postProcess = $postProcess;
        $this->locale = $locale;
        $this->asyncCorrelationData = $asyncCorrelationData;
    }

    /**
     * @return NameAddress
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param NameAddress $address
     * @return \ConnectshipBundle\AMP\ValidateAddressRequest
     */
    public function setAddress($address) {
        $this->address = $address;
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
     * @return \ConnectshipBundle\AMP\ValidateAddressRequest
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
     * @return \ConnectshipBundle\AMP\ValidateAddressRequest
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
     * @return \ConnectshipBundle\AMP\ValidateAddressRequest
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
     * @return \ConnectshipBundle\AMP\ValidateAddressRequest
     */
    public function setAsyncCorrelationData($asyncCorrelationData) {
        $this->asyncCorrelationData = $asyncCorrelationData;
        return $this;
    }

}
