<?php 
namespace App\Model;

class Plan {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
  public function getPlan() {
    $sql = "SELECT *  FROM plan_of_weeks";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    //get product list also 
    $productSQL = "SELECT product_id, name FROM products";
    $productData = $this->db->query($productSQL)->fetchAll(\PDO::FETCH_ASSOC);
    return array(
      'plans' => $data,
      'products' => $productData
    );
    
  }
  
}