<?php 
namespace App\Controllers;
use \App\Model\Chart;

class ReportController extends BaseController {
 
  public function index($request, $response) { 
    $chart = new Chart($this->db);
    $params = $request->getQueryParams();
    if(!isset($params['year'])) {
      $params['year'] = date("Y");
    }
    $type = '';
    $productId = '';
    if(isset($params['type'])) {
      $type = $params['type'];
    }
    if(isset($params['product-id'])) {
      $productId = $params['product-id'];
    }
    switch($type) {
      case 'weekofyear':
        $reportQuarter = $chart->reportByWeekOfYear($params['year'], $productId);
        break;
      case 'month':
        $reportQuarter = $chart->reportByMonthOfYear($params['year'], $productId);
        break;
      case 'quarter':
        $reportQuarter = $chart->reportByQuarter($params['year'], $productId);
        break;
      case 'year':
        $reportQuarter = $chart->reportByYear($params['year'], $productId);
        break;
      case 'products':
        $reportQuarter = $chart->reportByProducts($params['year'], $productId);
        break;
    }
    echo json_encode($reportQuarter);
  }
  public function chart($request, $response) {
    $data = [];
    return $this->view->render($response, 'chart.phtml', $data);
  }
  public function product($request, $response) {
    $data = array(
      'status' => 'error',
      'message' => 'Không tìm thấy sản phẩm nào!'
    );
    $product = $this->db->select('products', ['product_id', 'name']);
    if(!empty($product)) {
      $data['status'] = 'success';
      $data['message'] = 'Product loaded!';
      $data['data'] = $product;
    }
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-type', 'application/json');
  }
}