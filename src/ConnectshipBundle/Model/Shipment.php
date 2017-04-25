<?php

namespace ConnectshipBundle\Model;

class Shipment {

    private $trackingNumber;

    function getTrackingNumber() {
        return $this->trackingNumber;
    }

    function setTrackingNumber($trackingNumber) {
        $this->trackingNumber = $trackingNumber;
    }

}
