<?php 
namespace App\Controllers;
use \Slim\Views\PhpRenderer;
use \App\Helper\Data;
use \App\Model\Plan;
use \App\Model\Stores;

class HomeController extends BaseController {
  public function index($request, $response) {
//    $plan = new Plan($this->db);
//    $data = $plan->getPlanPerWeek(2018);
//    echo "<pre>";
//    print_r($data);
//    die;
    $helper = new \App\Helper\Data();
    $data = $helper->readExcel($this->baseDir() . 'resource/BanDonHangFormatChuan.xlsx');
    //Load district to compare 
    if(!isset($_SESSION['districtList'])) {
      $districtSQL = "SELECT district.code as 'district_id', CONCAT(district.name,'-', provinces.name) as 'title', district.name as 'huyen' FROM district LEFT JOIN provinces ON district.parent_code = provinces.code ORDER BY huyen";
      $districtData = $this->db->query($districtSQL)->fetchAll(\PDO::FETCH_ASSOC);
      $_SESSION['districtList'] = $districtData;
    }
    $districtList = $_SESSION['districtList'];
    $orderData = array();
    $compareStoreNameAndAddress = [];
    foreach($data as $order) {
      //Find district for this order info 
      $sourceText = isset($order['B']) ? $order['B'] : '';
      $district = $helper->findDistrict($sourceText, $districtList);
      
      $itemArr = array(
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
        'name_address' => ''
      );
      
      $nameAndAddress = $itemArr['name'] . $itemArr['address'];
      if($nameAndAddress != "") {
        //mb_strtolower FOR UTF-8 CODE
        $textToLower = mb_strtolower($nameAndAddress, 'UTF-8');
        $textTemp = str_replace(' ', '',$textToLower);
        $compareStoreNameAndAddress[] = "'" . $textTemp . "'";  
        $itemArr['name_address'] = $textTemp;
      }
      $orderData[] = $itemArr;
    }
    //Load all stores exists in database 
    $existsStore = [];
    if(!empty($compareStoreNameAndAddress)) {
      $store = new Stores($this->db);
      $existsStore = $store->checkExistsStores(implode($compareStoreNameAndAddress, ','));
    }
    //If found exists store, then update store_id for that store
    if(!empty($existsStore)) {
      for($i = 0; $i < count($orderData); $i++) {
        foreach($existsStore as $store) {
          if($store['title'] == $orderData[$i]['name_address']) {
            $orderData[$i]['store_id'] = $store['store_id'];
          }
        }
      }
    }
//    echo "<pre>";
//    print_r($orderData);
//    die;
    echo json_encode($orderData);
    die;
    $data = [];
    return $this->view->render($response, 'home.phtml', $data);
  }
}