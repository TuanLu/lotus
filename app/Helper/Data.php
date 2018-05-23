<?php 
namespace App\Helper;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Data {
  /**
   * Format date for mysql database
   */
  public function convertStringToDate($format, $dateString) {
    $dt = \DateTime::createFromFormat($format, $dateString);
    if($dt instanceof \DateTime) {
      return $dt->format('Y-m-d');
    } else {
      //Normal date format will be: 13/09/17. The data must be the same date format 
      //Try another date format: 13/09/2017. 
      $dt = \DateTime::createFromFormat('d/m/Y', $dateString);
      if($dt instanceof \DateTime) {
        return $dt->format('Y-m-d');
      }
    }
  }
  public function readExcel($path) {
    if(file_exists($path)) {
      $reader = new Xlsx();
      $spreadsheet = $reader->load($path);
      $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
      //Remove title row of excel
      unset($sheetData[1]);
      return $sheetData;
    } else {
      echo "File: $path not exitst!";
    }
    return [];
  }
}