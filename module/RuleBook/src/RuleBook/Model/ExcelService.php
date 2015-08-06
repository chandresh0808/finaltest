<?php
 
namespace RuleBook\Model;
 
use RuleBook\Model\ChunkReadFilter;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Reader_Exception;

class ExcelService extends \Application\Model\AbstractCommonServiceMutator
{
 

    /**
     * Excel object
     *
     * @var PHPExcel
     */
    protected $excel;

    public function __construct()
    {
        $this->excel = new PHPExcel();
    }

    /**
     * Get php excel object
     *
     * @return \Application\Service\ExcelService
     */
    Public function getPHPExcel()
    {
        return $this->excel;
    }

    /**
     * Read file
     *
     * @param string $inputFileName            
     * @param array $options            
     * @return object ExcelObject
     */
    Public function readFile($inputFileName, $options = array())
    {
        try {
            $defaultOptions = array(
                'readDataOnly' => true,
            );
            $options = array_merge($defaultOptions, $options);
            // Identify the type of $inputFileName
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $readDataOnly = $options['readDataOnly'];
            // Create a new Reader of the type that has been identified
            $reader = PHPExcel_IOFactory::createReader($inputFileType);
            
            $reader->setReadDataOnly($readDataOnly);
            
            // Load $inputFileName to a PHPExcel Object
            return $reader->load($inputFileName);
        } catch (PHPExcel_Reader_Exception $e) {
            throw new \Exception('Error loading file: ' . $e->getMessage());
        }
    }

    /**
     * Write file
     *
     * @param PHPExcel $phpExcel            
     * @param string $writerType            
     * @return PHPExcel_Writer_IWriter
     */
    public function writeFile(PHPExcel $phpExcel, $writerType = '')
    {
        return PHPExcel_IOFactory::createWriter($phpExcel, $writerType);
    }

    /**
     * Get worksheet names
     *
     * @param string $inputFileName            
     * @return array $workSheetInfo
     */
    public function getWorksheetNames($inputFileName)
    {
        $workSheetNames = array();
        $objReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($inputFileName));
        $workSheetNames = $objReader->listWorksheetNames($inputFileName);
        return $workSheetNames;
    }

    /**
     * Get worksheet information
     *
     * @param string $inputFileName            
     * @return array $workSheetInfo
     */
    public function getWorksheetInfo($inputFileName)
    {
        $workSheetInfo = array();
        $objReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($inputFileName));
        $workSheetInfo = $objReader->listWorksheetInfo($inputFileName);
        return $workSheetInfo;
    }

    /**
     * Get active sheet data
     *
     * @param PHPExcel $reader            
     * @return array $sheetData
     */
    public function getActiveSheetData($reader)
    {
        $sheetData = array();
        if (is_object($reader)) {
            $sheetData = $reader->getActiveSheet()->toArray(null, true, true, false);
        }
        return $sheetData;
    }

    /**
     * Used to read data from excel file in chunks
     *
     * @param string $inputFileName            
     * @param string $sheetName            
     * @param array $options            
     * @return array $rows
     */
    public function readFileChunks($inputFileName, $workSheet, $options = array())
    {
        $rows = array();
        // Create a new Reader of the type that has been identified
        $objReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($inputFileName));
        
        $defaultOptions = array(
                'readDataOnly'  => true,
                'chunkSize'     => 2048
            );
        $options = array_merge($defaultOptions, $options);
            
        // Create a new Instance of our Read Filter
        $chunkFilter = new ChunkReadFilter();
        $chunkSize = $options['chunkSize'];
        $readDataOnly = $options['readDataOnly'];
        
        // Tell the Reader that we want to use the Read Filter that we've Instantiated
        $objReader->setReadFilter($chunkFilter);
        $objReader->setReadDataOnly($readDataOnly);
        $objReader->setLoadSheetsOnly($workSheet['worksheetName']);
        
        // get header column name
        $chunkFilter->setRows(0, 1);
        $objPHPExcel = $objReader->load($inputFileName);
        $totalRows = $workSheet['totalRows'];
        
        // Loop to read our worksheet in "chunk size" blocks
        // $startRow is set to 1 initially because we always read the headings in row #1
        for ($startRow = 1; $startRow <= $totalRows; $startRow += $chunkSize) {
            // Tell the Read Filter, the limits on which rows we want to read this iteration
            $chunkFilter->setRows($startRow, $chunkSize);
            
            // Load only the rows that match our filter from $inputFileName to a PHPExcel Object
            $objPHPExcel = $objReader->load($inputFileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
            
            if (! empty($sheetData) && $startRow < $totalRows) {
               $rows = $this->cleanArray($sheetData);
            }
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel, $sheetData);
            /* removes duplicate key - value pair */
            $rows = array_map("unserialize", array_unique(array_map("serialize", $rows)));
            return $rows;
        }
    }
    
    
    /*
     * It will remove key from array which doesn't have value
     */
    public function cleanArray($array, $isRepeat = false) {
        foreach ($array as $key => $value) {
            if ($value === null || $value === '') { unset($array[$key]); }
            else if (is_array($value)) {
                if (empty($value)) { unset($array[$key]); }
                else $array[$key] = $this->cleanArray($value);
            }
        }
        if (!$isRepeat) {
            $array = $this->cleanArray($array,true);
        }
        return $array;
    }
    
}
