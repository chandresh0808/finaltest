<?php

namespace RuleBook\Model;

use Zend\Validator\File\Extension;
use Zend\Validator\File\MimeType;

abstract class BaseExcelFile
{

    protected $_fileName = null;

    protected $_fileExtentions = array();

    protected $_fileMimeType = array();

    public function __construct()
    {}

    /**
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     *
     * @param string $fileName            
     * @return \Import\Model\BaseExcelFile
     */
    public function setFileName($fileName)
    {
        $this->_fileName = $fileName;
        return $this;
    }

    /**
     * Checks whether a file exists
     *
     * @return boolean
     */
    public function isFileExist()
    {
        $fileName = $this->getFileName();
        $isfileExist = false;
        if (file_exists($fileName)) {
            $isfileExist = true;
        }
        return $isfileExist;
    }

    /**
     * get file extions.
     *
     * @return array $this->_fileExtentions
     */
    public function getFileExtension()
    {
        return $this->_fileExtentions;
    }

    /**
     * set file extions.
     *
     * @param array $extentions            
     * @return \Import\Model\BaseExcelFile
     */
    public function setFileExtion($extentions = array())
    {
        $this->_fileExtentions = $extentions;
        return $this;
    }

    /**
     * get file mimeType.
     *
     * @return array $this->_fileMimeType
     */
    public function getFileMimeType()
    {
        return $this->_fileMimeType;
    }

    /**
     * set file mimeType.
     *
     * @param array $mimeType            
     * @return \Import\Model\BaseExcelFile
     */
    public function setFileMimeType($mimeType = array())
    {
        $this->_fileMimeType = $mimeType;
        return $this;
    }

    /**
     * Checks whether a valid file extension.
     */
    public function isValidFileExtension()
    {
        $fileName = $this->getFileName();
        $extentions = $this->getFileExtension();
        $isValid = false;
        $extentionValidator = new Extension($extentions);
        if ($extentionValidator->isValid($fileName)) {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * Checks whether a valid file.
     */
    public function isValidFile()
    {
        $fileName = $this->getFileName();
        $mimeType = $this->getFileMimeType();
        $isValid = false;
        $extentionValidator = new MimeType($mimeType);
        if ($extentionValidator->isValid($fileName)) {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * Used to validate set up criteria file headers
     */
    abstract public function validateFileHeaders();

    /**
     * Get inavlid file
     */
    abstract public function getInvalidFile();

    /**
     * Read each row from excel file
     */
    abstract public function processFile();
}


