<?php 
namespace App\Controllers;
use \Slim\Views\PhpRenderer;
use \App\Helper\Data;

class HomeController extends BaseController {
  public function index($request, $response) {
    $helper = new \App\Helper\Data();
    $data = $helper->readExcel($this->baseDir() . 'resource/BanDonHangFormatChuan.xlsx');
    //Load district to compare 
    $districtSQL = "SELECT district.code as 'district_id', CONCAT(district.name,'-', provinces.name) as 'title', district.name as 'huyen' FROM district LEFT JOIN provinces ON district.parent_code = provinces.code ORDER BY huyen";
    if(!isset($_SESSION['districtList'])) {
      $districtData = $this->db->query($districtSQL)->fetchAll(\PDO::FETCH_ASSOC);
      $_SESSION['districtList'] = $districtData;
    }
    $districtList = $_SESSION['districtList'];
    $orderData = array();
    foreach($data as $order) {
      //Find district for this order info 
      $sourceText = isset($order['B']) ? $order['B'] : '';
      $district = $this->findDistrict($sourceText, $districtList);
      
      $orderData[] = array(
        'store_id' => '',
        'name' => isset($order['A']) ? $order['A'] : '',
        'address' => isset($order['B']) ? $order['B'] : '',
        'product_id' => isset($order['C']) ? $order['C'] : '',
        'delivery_id' => isset($order['D']) ? $order['D'] : '',
        'date' => isset($order['E']) ? $order['E'] : '',
        'qty' => isset($order['F']) ? $order['F'] : '',
        'price' => isset($order['G']) ? $order['G'] : '',
        'unit' => isset($order['H']) ? $order['H'] : '',
        'district_id' => !empty($district) ? $district['district_id'] : '',
        'district_name' => !empty($district) ? $district['huyen'] : '',
      );
    }
    echo json_encode($orderData);
    die;
    $data = [];
    return $this->view->render($response, 'home.phtml', $data);
  }
  protected function findDistrict($sourceText, $districtList) {
    if($sourceText != "") {
      $sourceText = strtolower($sourceText);
      //Remove space 
      $sourceText = str_replace(' ', '', $sourceText);
      $districtTemp = '';
      $foundArr = [];
      foreach($districtList as $district) {
        $districtTemp = strtolower($district['huyen']);
        //replace 'huyen', 'quan', 'thanhpho' => ''
        $districtTemp = str_replace('thành phố', '', $districtTemp);
        $districtTemp = str_replace('quận', '', $districtTemp);
        $districtTemp = str_replace('huyện', '', $districtTemp);
        $districtTemp = str_replace(' ', '', $districtTemp);
        //Special string Quận 1 -> 12 
        if(is_numeric($districtTemp)) {
          $districtTemp = 'quận' . $districtTemp;
        }
        if(strpos($sourceText, $districtTemp)) {
          $foundArr[] = $district;
        }
      }
      if(count($foundArr) == 1) {        
        return $foundArr[0];
      }
    }
  }
}