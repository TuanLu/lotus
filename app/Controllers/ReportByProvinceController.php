<?php 
namespace App\Controllers;
use \App\Model\ProvinceChart;

class ReportByProvinceController extends BaseController {
 
  public function index($request, $response) { 
    $params = $request->getQueryParams();
    if(!isset($params['year'])) {
      $params['year'] = date("Y");
    }
    $filterParams = ['type', 'product-id', 'area', 'quarter', 'month', 'week'];
    foreach($filterParams as $filterOption) {
      $params[$filterOption] = isset($params[$filterOption]) ? $params[$filterOption] : '';
    }
    if($params['type'] == '') {
      return json_encode(array(
        'status' => 'error',
        'message' => 'The type of chart is empty!'
      ));
    }
    $chart = new ProvinceChart($this->db);
    $reportData = array();
    switch($params['type']) {
      case 'provinces':
        $reportData = $chart->reportByProvinceYear($params['year'], $params['product-id'],$params['area']);
        break;
      case 'provinces_quarter':
        $reportData = $chart->reportByProvovinceQuarter($params['year'], $params['product-id'], $params['area'], $params['quarter']);
        break;
      case 'provinces_month':
        $reportData = $chart->reportByProvovinceMonth($params['year'], $params['product-id'], $params['area'], $params['month']);
        break;
      case 'provinces_week':
        $reportData = $chart->reportByProvovinceWeek($params['year'], $params['product-id'], $params['area'], $params['week']);
        break;
    }
    echo json_encode($reportData);
  }
}