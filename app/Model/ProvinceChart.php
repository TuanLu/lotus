<?php 
namespace App\Model;

class ProvinceChart {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
  public function reportByProvinceYear($year, $productId, $area) {
    $where = "YEAR(date) = $year";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT tinh,mien, sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu' from (
select district.code, areas.area_code as 'ma_mien', areas.name as 'mien', provinces.name as 'tinh' from district,provinces,areas WHERE district.parent_code = provinces.code AND provinces.area_code = areas.area_code
) as khuvuc, orders,nha_thuoc WHERE orders.store_id = nha_thuoc.store_id AND nha_thuoc.district_id = khuvuc.code AND ". $where ." GROUP BY mien,tinh ORDER BY doanhthu DESC";
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
  public function reportByProvovinceQuarter($year, $productId, $area, $quarter) {
    $where = "YEAR(date) = $year AND QUARTER(date) = $quarter";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT tinh,mien, sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu' from (
select district.code, areas.area_code as 'ma_mien', areas.name as 'mien', provinces.name as 'tinh' from district,provinces,areas WHERE district.parent_code = provinces.code AND provinces.area_code = areas.area_code
) as khuvuc, orders,nha_thuoc WHERE orders.store_id = nha_thuoc.store_id AND nha_thuoc.district_id = khuvuc.code AND ". $where ." GROUP BY mien,tinh ORDER BY doanhthu DESC";
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
		'text' => "Tổng doanh số các tỉnh quý $quarter năm $year"
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
  public function reportByProvovinceMonth($year, $productId, $area, $month) {
    $where = "YEAR(date) = $year AND MONTH(date) = $month";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT tinh,mien, sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu' from (
select district.code, areas.area_code as 'ma_mien', areas.name as 'mien', provinces.name as 'tinh' from district,provinces,areas WHERE district.parent_code = provinces.code AND provinces.area_code = areas.area_code
) as khuvuc, orders,nha_thuoc WHERE orders.store_id = nha_thuoc.store_id AND nha_thuoc.district_id = khuvuc.code AND ". $where ." GROUP BY mien,tinh ORDER BY doanhthu DESC";
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
		'text' => "Tổng doanh số các tỉnh tháng $month năm $year"
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
    $barChart['height'] = 300;
    return $barChart;
  }
  public function reportByProvovinceWeek($year, $productId, $area, $week) {
    $where = "YEAR(date) = $year AND WEEKOFYEAR(date) = $week";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT tinh,mien, sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu' from (
select district.code, areas.area_code as 'ma_mien', areas.name as 'mien', provinces.name as 'tinh' from district,provinces,areas WHERE district.parent_code = provinces.code AND provinces.area_code = areas.area_code
) as khuvuc, orders,nha_thuoc WHERE orders.store_id = nha_thuoc.store_id AND nha_thuoc.district_id = khuvuc.code AND ". $where ." GROUP BY mien,tinh ORDER BY doanhthu DESC";
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
		'text' => "Tổng doanh số các tỉnh tuần $week năm $year"
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
    $barChart['height'] = 300;
    return $barChart;
  }
}