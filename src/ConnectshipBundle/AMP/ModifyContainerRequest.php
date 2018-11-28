<?php

namespace ConnectshipBundle\AMP;

class ModifyContainerRequest {

    /**
     * @var string $carrier
     */
    protected $carrier = null;

    /**
     * @var string $container
     */
    protected $container = null;

    /**
     * @var DataDictionary $containerData
     */
    protected $containerData = null;

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
     * @param string $container
     * @param DataDictionary $containerData
     * @param string $preProcess
     * @param string $postProcess
     * @param language $locale
     * @param string $asyncCorrelationData
     */
    public function __construct($carrier, $container, $containerData, $preProcess, $postProcess, $locale, $asyncCorrelationData) {
        $this->carrier = $carrier;
        $this->container = $container;
        $this->containerData = $containerData;
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
     * @return \ConnectshipBundle\AMP\ModifyContainerRequest
     */
    public function setCarrier($carrier) {
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * @return string
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * @param string $container
     * @return \ConnectshipBundle\AMP\ModifyContainerRequest
     */
    public function setContainer($container) {
        $this->container = $container;
        return $this;
    }

    /**
     * @return DataDictionary
     */
    public function getContainerData() {
        return $this->containerData;
    }

    /**
     * @param DataDictionary $containerData
     * @return \ConnectshipBundle\AMP\ModifyContainerRequest
     */
    public function setContainerData($containerData) {
        $this->containerData = $containerData;
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
     * @return \ConnectshipBundle\AMP\ModifyContainerRequest
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
     * @return \ConnectshipBundle\AMP\ModifyContainerRequest
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
     * @return \ConnectshipBundle\AMP\ModifyContainerRequest
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
     * @return \ConnectshipBundle\AMP\ModifyContainerRequest
     */
    public function setAsyncCorrelationData($asyncCorrelationData) {
        $this->asyncCorrelationData = $asyncCorrelationData;
        return $this;
    }

}
