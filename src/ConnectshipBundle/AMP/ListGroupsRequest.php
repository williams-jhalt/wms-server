<?php

namespace ConnectshipBundle\AMP;

class ListGroupsRequest {

    /**
     * @var string $carrier
     */
    protected $carrier = null;

    /**
     * @var string $grouping
     */
    protected $grouping = null;

    /**
     * @var string $status
     */
    protected $status = null;

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
     * @param string $grouping
     * @param string $preProcess
     * @param string $postProcess
     * @param language $locale
     * @param string $asyncCorrelationData
     */
    public function __construct($carrier, $grouping, $preProcess, $postProcess, $locale, $asyncCorrelationData) {
        $this->carrier = $carrier;
        $this->grouping = $grouping;
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
     * @return \ConnectshipBundle\AMP\ListGroupsRequest
     */
    public function setCarrier($carrier) {
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * @return string
     */
    public function getGrouping() {
        return $this->grouping;
    }

    /**
     * @param string $grouping
     * @return \ConnectshipBundle\AMP\ListGroupsRequest
     */
    public function setGrouping($grouping) {
        $this->grouping = $grouping;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     * @return \ConnectshipBundle\AMP\ListGroupsRequest
     */
    public function setStatus($status) {
        $this->status = $status;
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
     * @return \ConnectshipBundle\AMP\ListGroupsRequest
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
     * @return \ConnectshipBundle\AMP\ListGroupsRequest
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
     * @return \ConnectshipBundle\AMP\ListGroupsRequest
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
     * @return \ConnectshipBundle\AMP\ListGroupsRequest
     */
    public function setAsyncCorrelationData($asyncCorrelationData) {
        $this->asyncCorrelationData = $asyncCorrelationData;
        return $this;
    }

}
