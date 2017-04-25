<?php

namespace ConnectshipBundle\AMP;

class OutputItemList
{

    /**
     * @var base64Binary[] $binaryOutput
     */
    protected $binaryOutput = null;

    /**
     * @var string[] $outputFile
     */
    protected $outputFile = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return base64Binary[]
     */
    public function getBinaryOutput()
    {
      return $this->binaryOutput;
    }

    /**
     * @param base64Binary[] $binaryOutput
     * @return \ConnectshipBundle\AMP\OutputItemList
     */
    public function setBinaryOutput(array $binaryOutput = null)
    {
      $this->binaryOutput = $binaryOutput;
      return $this;
    }

    /**
     * @return string[]
     */
    public function getOutputFile()
    {
      return $this->outputFile;
    }

    /**
     * @param string[] $outputFile
     * @return \ConnectshipBundle\AMP\OutputItemList
     */
    public function setOutputFile(array $outputFile = null)
    {
      $this->outputFile = $outputFile;
      return $this;
    }

}
