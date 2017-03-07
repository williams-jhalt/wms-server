<?php

namespace AppBundle\Model;

use DateTime;

class SalesOrder extends \Williams\ErpBundle\Model\SalesOrder {

    const COMPANY_MUFFS = 'muffs';
    const COMPANY_WILLIAMS = 'williams';
    const SOURCE_WEBSITE = 'website';
    const SOURCE_CSR = 'csr';

    protected $websiteOrderDate;
    protected $websiteNotes;
    protected $shippingDate;
    protected $company;
    protected $source;

    /**
     * 
     * @return DateTime
     */
    public function getShippingDate() {
        return $this->shippingDate;
    }

    /**
     * 
     * @param DateTime $shippingDate
     * @return SalesOrder
     */
    public function setShippingDate($shippingDate) {
        $this->shippingDate = $shippingDate;
        return $this;
    }

    /**
     * 
     * @return DateTime
     */
    public function getWebsiteOrderDate() {
        return $this->websiteOrderDate;
    }

    /**
     * 
     * @return string
     */
    public function getWebsiteNotes() {
        return $this->websiteNotes;
    }

    /**
     * 
     * @param DateTime $websiteOrderDate
     * @return SalesOrder
     */
    public function setWebsiteOrderDate($websiteOrderDate) {
        $this->websiteOrderDate = $websiteOrderDate;
        return $this;
    }

    /**
     * 
     * @param DateTime $websiteNotes
     * @return SalesOrder
     */
    public function setWebsiteNotes($websiteNotes) {
        $this->websiteNotes = $websiteNotes;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getWebsiteId() {
        return preg_replace("/WEB(\d+)/", "$1", $this->webReferenceNumber);
    }

    /**
     * 
     * @return string
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * 
     * @param string $company
     * @return SalesOrder
     */
    public function setCompany($company) {
        $this->company = $company;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * 
     * @param String $source
     * @return SalesOrder
     */
    public function setSource($source) {
        $this->source = $source;
        return $this;
    }

}
