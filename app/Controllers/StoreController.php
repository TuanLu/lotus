<?php 
namespace App\Controllers;
use \App\Model\Stores;

class StoreController extends BaseController {
  public function index($request, $response) {
    $store = new Stores($this->db);
    $rsData = array(
      'status' => 'success',
      'message' => 'Không tìm thấy nhà thuốc nào!',
      'data' => []
    );
    $stores = $store->getStores();
    if(!empty($stores)) {
      $rsData['message'] = 'Dữ liệu nhà thuốc đã được load';
      $rsData['data'] = $stores;
    }
    $response->getBody()->write(json_encode($rsData));
    return $response->withHeader('Content-type', 'application/json');
  }
  /**
   * Get all provinces and all districts of Vietnam
   **/
  public function location($request, $response) {
    $rsData = array(
      'status' => 'success',
      'message' => 'Không tìm thấy tỉnh, huyện nào!',
      'data' => []
    );
    $store = new Stores($this->db);
    $locationData = $store->getLocation();
    if(isset($locationData['district']) && !empty($locationData['district'])) {
      $rsData['message'] = 'Dữ liệu tỉnh, huyện đã được load!';
      $rsData['data'] = $locationData;
    }
    $response->getBody()->write(json_encode($rsData));
    return $response->withHeader('Content-type', 'application/json');
  }
  public function updatestore($request, $response) {
    $rsData = array(
      'status' => 'error',
      'message' => 'Dữ liệu chưa được cập nhật thành công',
    );
    $params = $request->getParams();
    $store = new Stores($this->db);
    $result = $store->updateStore($params);
    if($result) {
      $rsData = array(
        'status' => 'success',
        'message' => 'Dữ liệu đã được cập nhật',
        'data' => $params
      );
    }
    $response->getBody()->write(json_encode($rsData));
    return $response->withHeader('Content-type', 'application/json');
  }
  public function deletestore($request, $response, $args) {
    $rsData = array(
      'status' => 'error',
      'message' => 'Dữ liệu chưa được xoá thành công',
    );
    $id = $args['id'];
    $store = new Stores($this->db);
    $result = $store->deleteStore($id);
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
}