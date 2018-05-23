<?php 
namespace App\Controllers;
use \Slim\Views\PhpRenderer;
use \App\Helper\Data;

class HomeController extends BaseController {
  public function index($request, $response) {
    die('Home Controller');
    $helper = new \App\Helper\Data();
    //echo $helper->convertStringToDate('d/m/y', "25/12/16");
    $filePath = $this->baseDir() . 'resource/orders.xlsx';
    unset($_SESSION['orders']);
    if(!isset($_SESSION['orders'])) {
      $data = $helper->readExcel($filePath);
      $_SESSION['orders'] = $data;
    } else {
      $data = $_SESSION['orders'];
    }   


    $validDataArr = [];
    foreach($data as $order) {
      $validDataArr[] = array(
        "store_id" => $order['C'],
        "product_id" => $order['D'],
        "price" => $order['E'],
        "qty" => $order['B'],
        "date" => $helper->convertStringToDate('d/m/y', $order['A']),
      );
    }
    // echo "<pre>";
    // print_r(count($validDataArr));
    // die;
     //Database instance
    
    //$result = $this->db->insert('orders', $validDataArr);


    die('Done import: ' . $result->rowCount());

    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-type', 'application/json');
  }
}