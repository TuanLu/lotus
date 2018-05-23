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
    if(isset($params['type'])) {
      $type = $params['type'];
    }
    switch($type) {
      case 'weekofyear':
        $reportQuarter = $chart->reportByWeekOfYear($params['year']);
        break;
      case 'month':
        $reportQuarter = $chart->reportByMonthOfYear($params['year']);
        break;
      case 'products':
        $reportQuarter = $chart->reportByProducts($params['year']);
        break;
      default:
        $reportQuarter = $chart->reportByQuarter($params['year']);    
        break;
    }
    echo json_encode($reportQuarter);
  }
  public function chart($request, $response) {
    $data = [];
    return $this->view->render($response, 'chart.phtml', $data);
  }
}