<?php 
namespace App\Controllers;
use \App\Model\Orders;

class OrderController extends BaseController {
  public function index($request, $response) {
    $store = new Orders($this->db);
    $rsData = array(
      'status' => 'success',
      'message' => 'Không tìm dữ liệu nào!',
      'data' => []
    );
    $stores = $store->getOrders();
    if(!empty($stores)) {
      $rsData['message'] = 'Dữ liệu đã được load';
      $rsData['data'] = $stores;
    }
    $response->getBody()->write(json_encode($rsData));
    return $response->withHeader('Content-type', 'application/json');
  }

  public function addorders($request, $response) {
    $rsData = array(
      'status' => 'error',
      'message' => 'Dữ liệu chưa được thêm thành công',
    );
    $params = $request->getParams();
    if(!empty($params)) {
      $order = new Orders($this->db);
      $result = $order->addOrders($params);
      if($result) {
        $rsData = array(
          'status' => 'success',
          'message' => 'Dữ liệu đã được thêm thành công',
          'data' => $result
        );
      } 
    }
    $response->getBody()->write(json_encode($rsData));
    return $response->withHeader('Content-type', 'application/json');
  }
  public function deleteorder($request, $response, $args) {
    $rsData = array(
      'status' => 'error',
      'message' => 'Dữ liệu chưa được xoá thành công',
    );
    $id = $args['id'];
    $store = new Orders($this->db);
    $result = $store->deleteOrder($id);
    if($result) {
      $rsData = array(
        'status' => 'success',
        'message' => 'Dữ liệu đã được xoá thành công',
        'data' => $id
      );
    }
    $response->getBody()->write(json_encode($rsData));
    return $response->withHeader('Content-type', 'application/json');
  }
  public function importOrderData($request, $response) {
    $rsData = array(
      'status' => 'success',
      'message' => 'Không tìm thấy mã nhà thuốc, mã sản phẩm!',
      'data' => []
    );
    $order = new Orders($this->db);
    $preOrderData = $order->getPreOrderData();
    if(isset($preOrderData['products']) 
       && !empty($preOrderData['products']) 
       && isset($preOrderData['stores']) 
       && !empty($preOrderData['stores'])) {
      $rsData['message'] = 'Dữ liệu đã được load!';
      $rsData['data'] = $preOrderData;
    }
    $response->getBody()->write(json_encode($rsData));
    return $response->withHeader('Content-type', 'application/json');
  }
}