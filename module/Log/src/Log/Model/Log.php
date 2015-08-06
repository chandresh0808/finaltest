<?php
namespace Log\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    protected $_logNameArray;
    protected static $_logManagerArray=array();

    public function __construct()
    {
        
    }

    /**
     * 
     * @param sting $folderPath Log Folder Path
     */
    public function setFolderPath($folderPath)
    {
        $this->folderPath = $folderPath;
    }
    
    /**
     * 
     * @return string Folder Path
     */
    public function getFolderPath()
    {
        return $this->folderPath;
    }
    
    /**
     * 
     * @param sting $fileName Log File name
     */
    public function setLogNameArray($fileNameArray)
    {
        $this->_logNameArray = $fileNameArray;
    }
    
    /**
     * 
     * @return string Log File name
     */
    public function getLogName($fileType = 'info')
    {
        return $this->_logNameArray[$fileType];
    }
    
    protected function getLogInstance ($channel)
    {
        if (!self::$_logManagerArray[$channel]) {
            $logObject = new Logger($channel);
            
            $filePath   = $this->getFolderPath() . '/' . $this->getLogName($channel);
            $detailConstant = strtoupper($channel);

            $detailMode = constant('Monolog\Logger::' . $detailConstant);
        
            $logObject->pushHandler(new StreamHandler($filePath, $detailMode));
            self::$_logManagerArray[$channel] = $logObject;
        }
        return self::$_logManagerArray[$channel];
    }

    /**
     * Get log manager
     *
     * @return object
     */
    public function getLogManager($channel='info')
    {
        return $this->getLogInstance($channel);
    }
    
    
    /**
     * Set information on usual activity
     *
     * @return object
     */
    public function info($message)
    {
        $this->getLogManager(__FUNCTION__)->addInfo($message);
    }
    
    /**
     * Set information for normal but significant events
     *
     * @return object
     */
    public function notice($message)
    {
        try {
            $this->getLogManager(__FUNCTION__)->addNotice($message);
        } catch (\Exception $e) {
            throw new \Exception(5002);
        }
    }
    
    /**
     * Set debug information
     *a
     * @return object
     */
    public function error($message)
    {   
        try {
            // Debug mode gives more info compared  to error mode which looks similar to info
            // Hence using debug mode.
            $this->getLogManager('debug')->addError($message);
        } catch (\Exception $e) {
            throw new \Exception(5002);
        }
    }
    
    /**
     * Set critical information
     *
     * @return object
     */
    public function critical($message)
    {        
        try {
            $this->getLogManager(__FUNCTION__)->addCritical($message);
        } catch (\Exception $e) {
            throw new \Exception(5002);
        }
    }
    
    /**
     * Set debug information
     *
     * @return object
     */
    public function debug($message)
    {        
        try {
            $this->getLogManager(__FUNCTION__)->addDebug($message);
        } catch (\Exception $e) {
            throw new \Exception(5002);
        }
    }
}