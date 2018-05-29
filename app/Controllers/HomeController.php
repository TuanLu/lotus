<?php 
namespace App\Controllers;
use \Slim\Views\PhpRenderer;
use \App\Helper\Data;

class HomeController extends BaseController {
  public function index($request, $response) {
    $helper = new \App\Helper\Data();
    $data = $helper->readExcel($this->baseDir() . 'resource/BanDonHangFormatChuan.xlsx');
    $orderData = array();
    foreach($data as $order) {
      $orderData[] = array(
        'store_id' => isset($order['A']) ? $order['A'] : '',
        'product_id' => isset($order['B']) ? $order['B'] : '',
        'delivery_id' => isset($order['C']) ? $order['C'] : '',
        'date' => isset($order['D']) ? $order['D'] : '',
        'qty' => isset($order['E']) ? $order['E'] : '',
        'price' => isset($order['F']) ? $order['F'] : '',
        'unit' => isset($order['G']) ? $order['G'] : '',
      );
    }
    echo json_encode($orderData);
    die;
    $data = [];
    return $this->view->render($response, 'home.phtml', $data);
  }
}