<?php

namespace ConnectshipBundle\AMP;

class Holiday {

    /**
     * @var string $friendlyName
     */
    protected $friendlyName = null;

    /**
     * @var string $symbol
     */
    protected $symbol = null;

    /**
     * @var date $holidayDate
     */
    protected $holidayDate = null;

    /**
     * @param string $friendlyName
     * @param string $symbol
     * @param date $holidayDate
     */
    public function __construct($friendlyName, $symbol, $holidayDate) {
        $this->friendlyName = $friendlyName;
        $this->symbol = $symbol;
        $this->holidayDate = $holidayDate;
    }

    /**
     * @return string
     */
    public function getFriendlyName() {
        return $this->friendlyName;
    }

    /**
     * @param string $friendlyName
     * @return \ConnectshipBundle\AMP\Holiday
     */
    public function setFriendlyName($friendlyName) {
        $this->friendlyName = $friendlyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol() {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     * @return \ConnectshipBundle\AMP\Holiday
     */
    public function setSymbol($symbol) {
        $this->symbol = $symbol;
        return $this;
    }

    /**
     * @return date
     */
    public function getHolidayDate() {
        return $this->holidayDate;
    }

    /**
     * @param date $holidayDate
     * @return \ConnectshipBundle\AMP\Holiday
     */
    public function setHolidayDate($holidayDate) {
        $this->holidayDate = $holidayDate;
        return $this;
    }

}
