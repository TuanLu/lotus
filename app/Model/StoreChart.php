<?php 
namespace App\Model;

class StoreChart {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
  public function reportByStoreYear($year, $productId, $province) {
    $where = "YEAR(date) = $year";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT orders.store_id, orders.product_id, sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu', orders.date, nha_thuoc.name, district.name as 'huyen', provinces.code as 'ma_tinh' FROM orders JOIN nha_thuoc ON orders.store_id = nha_thuoc.store_id JOIN district ON nha_thuoc.district_id = district.code JOIN provinces ON district.parent_code = provinces.code GROUP BY store_id HAVING ma_tinh = 1";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }
    $report = array();
    foreach($data as $item) {
      $report['labels'][] = $item['tinh'];
      $report['data'][] = $item['doanhthu'];
    }
    $chartData = array(
      'labels' => $report['labels'],
      'datasets' => array(
        [
          'label' => 'Doanh thu thực',
          'data' => $report['data'],
          'backgroundColor' => 'rgba(54, 162, 235, 1)',
        ]
      ),
    );
    $barChart['data'] = $chartData;
    //Chart options 
    $barChart['options'] = array(
      'maintainAspectRatio' => false,
      'showTooltip' => true,
      'title' => array(
        'display' => true,
		'text' => 'Tổng doanh số các tỉnh năm ' . $year
      ),
      'scales' => [
        'xAxes' => [
          [
            'ticks' => [
              'beginAtZero' => true
            ],
            'scaleLabel' => [
              'display' => false,
              'labelString' => "Biểu đồ doanh thu các năm",
              'fontStyle' => 'bold',
              'fontColor' => '#ccc'
            ]
          ]
        ],
        'yAxes' => [
          [
            'ticks' => [
              'beginAtZero' => true
            ],
            'scaleLabel' => [
              'display' => true,
              'labelString' => 'Triệu (VND)',
              'fontStyle' => 'bold',
              'fontColor' => '#ccc'
            ]
          ]
        ]
      ]
    );
    $barChart['legend'] = array(
      'display' => true,
      'usePointStyle' => true
      
    );
    $barChart['width'] = 300;
    //$barChart['height'] = 300;
    return $barChart;
  }
  
}