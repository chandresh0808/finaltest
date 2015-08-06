<?php

namespace RuleBook\Model;

use RuleBook\Model\BaseExcelFile;


class SetupCriteriaFile extends BaseExcelFile
{

    /**
     * Function to validate excel file header if required
     *
     * @see \Import\Model\BaseExcelFile::validateFileHeaders()
     */
    public function validateFileHeaders()
    {
        // get validation rules from config file
        $config = $this->getConfigService();
        $validationRules = $config['setup_criteria_validation_rules'];
        $headerStratRowNumber = $validationRules['headerRowNumber'];
        $excelService = $this->getService('excel_service');
        $fileName = $this->getFileName();
        $objPHPExcel = $excelService->readFile($fileName);
        $total_sheets = $objPHPExcel->getSheetCount();
        $allSheetName = $objPHPExcel->getSheetNames();
        $row = $objPHPExcel->getActiveSheet()
            ->getRowIterator($headerStratRowNumber)
            ->current();
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        
        foreach ($cellIterator as $cell) {
            // validate column here
            $cell->getValue();
        }
    }

    /**
     * Function to get invalid excel file
     *
     * @see \Import\Model\BaseExcelFile::getInvalidFile()
     */
    public function getInvalidFile()
    {
        // get invalid excel file details
    }

    /**
     * Function to process excel file and import into database
     *
     * @see \Import\Model\BaseExcelFile::processFile()
     */
    public function processFile()
    {
        // validate the and import into database
    }
}