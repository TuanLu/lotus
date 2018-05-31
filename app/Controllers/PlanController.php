<?php 
namespace App\Controllers;
use \App\Model\Plan;

class PlanController extends BaseController {

  public function index($request, $response) {
    $rsData = array(
      'status' => 'success',
      'message' => 'Không tìm dữ liệu nào!',
      'data' => []
    );
    $plan = new Plan($this->db); 
    $data = $plan->getPlan();
    if(isset($data['plans'])) {
      $rsData['plans'] = $data['plans'];
    }
    if(isset($data['products'])) {
      $rsData['products'] = $data['products'];
    }
    $response->getBody()->write(json_encode($rsData));
    return $response->withHeader('Content-type', 'application/json');
  }  
  public function updateplan() {
    
  }
  
}