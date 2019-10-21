<?php

namespace ErpBundle\Repository;

use DateTimeInterface;
use ErpBundle\Model\PackerLogEntryCollection;

interface PackerLogEntryRepositoryInterface {

    /**
     * 
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @param integer $limit
     * @param integer $offset
     * 
     * @return PackerLogEntryCollection
     */
    public function findByStartDateAndEndDate(DateTimeInterface $startDate, DateTimeInterface $endDate, $limit = 1000, $offset = 0);
    
    /**
     * 
     * @param string $userId
     * @param integer $limit
     * @param integer $offset
     * 
     * @return PackerLogEntryCollection
     */
    public function findByUserId($userId, $limit = 1000, $offset = 0);
    
    /**
     * 
     * @param string $ucc
     * @param integer $limit
     * @param integer $offset
     * 
     * @return PackerLogEntryCollection
     */
    public function findByUcc($ucc, $limit = 1000, $offset = 0);
    
}
