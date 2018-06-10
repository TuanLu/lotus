<?php 
namespace App\Controllers;
use \App\Model\Chart;
use \App\Model\AreaChart;

class ReportController extends BaseController {
 
  public function index($request, $response) { 
    $params = $request->getQueryParams();
    if(!isset($params['year'])) {
      $params['year'] = date("Y");
    }
    $filterParams = ['type', 'product-id', 'area'];
    foreach($filterParams as $filterOption) {
      $params[$filterOption] = isset($params[$filterOption]) ? $params[$filterOption] : '';
    }
    if($params['type'] == '') {
      return json_encode(array(
        'status' => 'error',
        'message' => 'The type of chart is empty!'
      ));
    }
    switch($params['type']) {
      case 'weekofyear':
      case 'month':
      case 'quarter':
      case 'year':
      case 'products':
        $chart = new Chart($this->db);
        break;
      case 'areas':
      case 'area_quarter':
      case 'area_month':
      case 'area_week':
        $areaChart = new AreaChart($this->db);
        break;
    }
    $reportData = array();
    switch($params['type']) {
      case 'weekofyear':
        $reportData = $chart->reportByWeekOfYear($params['year'], $params['product-id']);
        break;
      case 'month':
        $reportData = $chart->reportByMonthOfYear($params['year'], $params['product-id']);
        break;
      case 'quarter':
        $reportData = $chart->reportByQuarter($params['year'], $params['product-id']);
        break;
      case 'year':
        $reportData = $chart->reportByYear($params['year'], $params['product-id']);
        break;
      case 'areas':
        $reportData = $areaChart->reportByAreas($params['year'], $params['product-id'], $params['area']);
        break;
      case 'area_quarter':
        $reportData = $areaChart->reportByAreasQuarter($params['year'], $params['product-id'], $params['area']);
        break;
      case 'area_month':
        $reportData = $areaChart->reportByAreasMonth($params['year'], $params['product-id'], $params['area']);
        break;
      case 'area_week':
        $reportData = $areaChart->reportByAreasWeek($params['year'], $params['product-id'], $params['area']);
        break;
      case 'products':
        $reportData = $chart->reportByProducts($params['year'], $params['product-id']);
        break;
    }
    echo json_encode($reportData);
  }
  public function chart($request, $response) {
    $uri = $request->getUri();
    $data = [
      'base_url' => $uri->getBaseUrl()
    ];
    return $this->view->render($response, 'chart.phtml', $data);
  }
  public function product($request, $response) {
    $data = array(
      'status' => 'error',
      'message' => 'Không tìm thấy sản phẩm nào!'
    );
    $product = $this->db->select('products', ['product_id', 'name'], ['status' => 1]);
    if(!empty($product)) {
      $data['status'] = 'success';
      $data['message'] = 'Product loaded!';
      $data['data'] = $product;
    }
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-type', 'application/json');
  }
}