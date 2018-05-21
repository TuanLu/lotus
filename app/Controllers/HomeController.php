<?php 
namespace App\Controllers;
use \Slim\Views\PhpRenderer;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class HomeController extends BaseController {
  public function index($request, $response) {
    $filePath = $this->baseDir() . 'resource/Nha_thuoc_chuan_hoa_new.xlsx';
    if(!isset($_SESSION['data_nhathuoc'])) {
      $data = $this->readExcel($filePath);
      $_SESSION['data_nhathuoc'] = $data;
    } else {
      $data = $_SESSION['data_nhathuoc'];
    }
    //echo count($data);
    
    echo "<pre>";
    //print_r($data[2]);
    //Database instance
    $db = $this->db;

    $validDataArr = [];
    
    foreach($data as $product) {
      $validDataArr[] = array(
        "store_id" => $product['A'],
        "name" => $product['B'],
        "address" => $product['C'],
        "owner" => $product['D'],
        "phone" => $product['E'],
        "district" => $product['F'],
        "province" => $product['G'],
      );
    }
  
    $this->db->insert('nha_thuoc', $validDataArr);
    


    die('Done import');

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