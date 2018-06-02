<?php 
namespace App\Controllers;
use \App\Model\StoreChart;

class ReportByStoreController extends BaseController {
 
  public function index($request, $response) { 
    $params = $request->getQueryParams();
    if(!isset($params['year'])) {
      $params['year'] = date("Y");
    }
    $filterParams = ['type', 'product-id', 'province', 'quarter', 'month', 'week'];
    foreach($filterParams as $filterOption) {
      $params[$filterOption] = isset($params[$filterOption]) ? $params[$filterOption] : '';
    }
    if($params['type'] == '') {
      return json_encode(array(
        'status' => 'error',
        'message' => 'The type of chart is empty!'
      ));
    }
    $chart = new StoreChart($this->db);
    $reportData = array();
    switch($params['type']) {
      case 'stores':
        $reportData = $chart->reportByStoreYear($params['year'], $params['product-id'],$params['province']);
        break;
//      case 'provinces_quarter':
//        $reportData = $chart->reportByProvovinceQuarter($params['year'], $params['product-id'], $params['area'], $params['quarter']);
//        break;
//      case 'provinces_month':
//        $reportData = $chart->reportByProvovinceMonth($params['year'], $params['product-id'], $params['province'], $params['month']);
//        break;
//      case 'provinces_week':
//        $reportData = $chart->reportByProvovinceWeek($params['year'], $params['product-id'], $params['province'], $params['week']);
//        break;
    }
    echo json_encode($reportData);
  }
  public function provinces($request, $response) {
    $data = array(
      'status' => 'error',
      'message' => 'Không tìm thấy tỉnh nào nào!'
    );
    if(isset($_SESTION['province_list'])) {
      $provinceList = $_SESTION['province_list'];
    } else {
      $provinceList = $this->db->select('provinces', ['code', 'name']);
      $_SESTION['province_list'] = $provinceList;
    }
    if(!empty($provinceList)) {
      $data['status'] = 'success';
      $data['message'] = 'Provinces loaded!';
      $data['data'] = $provinceList;
    }
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-type', 'application/json');
  }
}