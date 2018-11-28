<?php

namespace ConnectshipBundle\AMP;

class ListCountryServicesRequest {

    /**
     * @var string $country
     */
    protected $country = null;

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
     * @param string $country
     * @param string $preProcess
     * @param string $postProcess
     * @param language $locale
     * @param string $asyncCorrelationData
     */
    public function __construct($country, $preProcess, $postProcess, $locale, $asyncCorrelationData) {
        $this->country = $country;
        $this->preProcess = $preProcess;
        $this->postProcess = $postProcess;
        $this->locale = $locale;
        $this->asyncCorrelationData = $asyncCorrelationData;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $country
     * @return \ConnectshipBundle\AMP\ListCountryServicesRequest
     */
    public function setCountry($country) {
        $this->country = $country;
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
     * @return \ConnectshipBundle\AMP\ListCountryServicesRequest
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
     * @return \ConnectshipBundle\AMP\ListCountryServicesRequest
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
     * @return \ConnectshipBundle\AMP\ListCountryServicesRequest
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
     * @return \ConnectshipBundle\AMP\ListCountryServicesRequest
     */
    public function setAsyncCorrelationData($asyncCorrelationData) {
        $this->asyncCorrelationData = $asyncCorrelationData;
        return $this;
    }

}
