<?php 
namespace App\Model;
use \App\Helper\Data;

class Orders {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
  public function getOrders() {
    $sql = "SELECT order_id, store_id, product_id, qty, price, date, unit, delivery_id,tdv  FROM orders ORDER BY date DESC";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    return $data;
  }
  public function addOrders($data) {
    $helper = new Data();
    $isOk = true;
    //Get last ID number from last_store_id table 
    $lastIdArr = $this->db->select('store_last_id', ['number','prefix'], ['id' => 1]);
    //New store arr data 
    $newStores = [];
    $newOrders = [];
    $lastStoreNumber = $lastIdArr[0]['number'];
    $updateCount = 0;
    for($i = 0; $i < count($data); $i++) {
      if(isset($data[$i]['order_id']) && $data[$i]['order_id'] != '') {
        //Edit order
        //Convert date to MYSQL date format 
        $data[$i]['date'] = $helper->convertStringToDate('d/m/Y', $data[$i]['date']);
        $result = $this->db->update('orders', [
          "store_id" => $data[$i]['store_id'],
          "date" => $data[$i]['date'],
          "product_id" => $data[$i]['product_id'],
          "delivery_id" => $data[$i]['delivery_id'],
          "qty" => $data[$i]['qty'],
          "price" => $data[$i]['price'],
          "unit" => $data[$i]['unit'],
          "tdv" => $data[$i]['tdv'],
        ], ['order_id' => $data[$i]['order_id']]);
        $updateCount = $result->rowCount();
      } else {
        //New order mode
        if(!isset($data[$i]['product_id']) || $data[$i]['product_id'] == ''
          || !isset($data[$i]['date']) || $data[$i]['date'] == '') {
          $isOk = false;
          return false;
        }
        if($data[$i]['store_id'] == '') {
          $newStoreId = $lastIdArr[0]['prefix'] . $lastStoreNumber;
          $newStores[] = [
            "store_id" => $newStoreId,
            "name" => $data[$i]['name'],
            "address" => $data[$i]['address'],
            "district_id" => $data[$i]['district_id'],
          ];
          $data[$i]['store_id'] = $newStoreId;
          //Increment last store number
          $lastStoreNumber += 1;
        }
        //Convert date to MYSQL date format 
        $data[$i]['date'] = $helper->convertStringToDate('d/m/Y', $data[$i]['date']);
        $newOrders[] = [
          "store_id" => $data[$i]['store_id'],
          "date" => $data[$i]['date'],
          "product_id" => $data[$i]['product_id'],
          "delivery_id" => $data[$i]['delivery_id'],
          "qty" => $data[$i]['qty'],
          "price" => $data[$i]['price'],
          "unit" => $data[$i]['unit'],
          "tdv" => $data[$i]['tdv'],
        ];
      }
    }
    if($isOk) {
      //If there are any new store, then create stores first 
      if(!empty($newStores)) {
        $result = $this->db->insert('nha_thuoc', $newStores);
        //update $lastStoreNumber
        if($result->rowCount()) {
          $this->db->update('store_last_id', ['number' => $lastStoreNumber],['id' => 1]);
        }
      }
      if(!empty($newOrders)) {
        $result = $this->db->insert('orders', $newOrders);
        return $result->rowCount();  
      }
    }
    return $updateCount;
  }
  public function deleteOrder($orderId) {
    $result = $this->db->delete('orders', [
      'order_id' => $orderId
    ]);
    return $result->rowCount();
  }
  public function getPreOrderData() {
    $productSQL = "SELECT product_id, name FROM products";
    $storeSQL = "SELECT store_id, CONCAT(store_id, '-', name) as 'title', address, district_id, name FROM nha_thuoc";
    $deliverySQL = "SELECT delivery_id,name FROM delivery";
    //Load district to compare 
    if(!isset($_SESSION['districtList'])) {
      $districtSQL = "SELECT district.code as 'district_id', CONCAT(district.name,'-', provinces.name) as 'title', district.name as 'huyen' FROM district LEFT JOIN provinces ON district.parent_code = provinces.code ORDER BY huyen";
      $districtData = $this->db->query($districtSQL)->fetchAll(\PDO::FETCH_ASSOC);
      $_SESSION['districtList'] = $districtData;
    }
    $districtList = $_SESSION['districtList'];
    $productData = $this->db->query($productSQL)->fetchAll(\PDO::FETCH_ASSOC);
    $storeData = $this->db->query($storeSQL)->fetchAll(\PDO::FETCH_ASSOC);
    $deliveryData = $this->db->query($deliverySQL)->fetchAll(\PDO::FETCH_ASSOC);
    //$districtData = $this->db->query($districtSQL)->fetchAll(\PDO::FETCH_ASSOC);
    return array(
      'products' => $productData,
      'stores' => $storeData,
      'deliveries' => $deliveryData,
      'districts' => $districtList
    );
  }
}