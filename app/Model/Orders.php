<?php 
namespace App\Model;
use \App\Helper\Data;

class Orders {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
  public function getOrders() {
    $sql = "SELECT order_id, store_id, product_id, qty, price, date, unit  FROM orders ORDER BY date DESC";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    return $data;
  }
  public function addOrders($data) {
    $helper = new Data();
    //Convert date to MYSQL date format 
    for($i = 0; $i < count($data); $i++) {
      $data[$i]['date'] = $helper->convertStringToDate('d/m/Y', $data[$i]['date']);
    }
    $result = $this->db->insert('orders', $data);
    return $result->rowCount();
  }
  public function deleteOrder($storeId) {
    
  }
  public function getPreOrderData() {
    $productSQL = "SELECT product_id, name FROM products";
    $storeSQL = "SELECT store_id,CONCAT(store_id, '-', name) as 'title', address FROM nha_thuoc";
    $deliverySQL = "SELECT delivery_id,name FROM delivery";
    $productData = $this->db->query($productSQL)->fetchAll(\PDO::FETCH_ASSOC);
    $storeData = $this->db->query($storeSQL)->fetchAll(\PDO::FETCH_ASSOC);
    $deliveryData = $this->db->query($deliverySQL)->fetchAll(\PDO::FETCH_ASSOC);
    return array(
      'products' => $productData,
      'stores' => $storeData,
      'deliveries' => $deliveryData,
    );
  }
}