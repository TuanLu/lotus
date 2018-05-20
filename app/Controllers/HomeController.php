<?php 
namespace App\Controllers;
use \Slim\Views\PhpRenderer;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class HomeController extends BaseController {
  public function index($request, $response) {
    $filePath = $this->baseDir() . 'resource/data.xlsx';
    $data = $this->readExcel($filePath);

    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-type', 'application/json');
  }
  public function readExcel($path) {
    if(file_exists($path)) {
      $reader = new Xlsx();
      $spreadsheet = $reader->load($path);
      $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
      //Remove title row of excel
      unset($sheetData[1]);
      return $sheetData;
    }
    return [];
  }
}