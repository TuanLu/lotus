<?php 
namespace App\Model;

class AreaChart {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
  public function reportByAreas($year, $productId, $area) {
    $where = "YEAR(date) = $year";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT code, mien,sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu' from (
select district.code, areas.area_code as 'ma_mien', areas.name as 'mien' from district,provinces,areas WHERE district.parent_code = provinces.code AND provinces.area_code = areas.area_code
) as khuvuc, orders,nha_thuoc WHERE orders.store_id = nha_thuoc.store_id AND nha_thuoc.district_id = khuvuc.code AND ". $where ." GROUP BY mien ORDER BY doanhthu DESC";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }
    $report = array();
    foreach($data as $quy) {
      $report['labels'][] = "Miền " . $quy['mien'];
      $report['data'][] = $quy['doanhthu'];
    }
    $chartData = array(
      'labels' => $report['labels'],
      'datasets' => array(
        [
          'label' => 'Mục tiêu',
          'data' => [0.5,0.7,1],
          'backgroundColor' => 'rgba(255, 99, 132, 1)',
        ],
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
		'text' => 'Doanh số các miền năm ' . $year
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
    $barChart['height'] = 200;
    return $barChart;
  }
  public function reportByAreasQuarter($year, $productId, $area) {
    $where = "YEAR(date) = $year";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT code, khuvuc.ma_mien, QUARTER(orders.date) as 'quy',sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu' from (
select district.code, areas.area_code as 'ma_mien', areas.name as 'mien' from district,provinces,areas WHERE district.parent_code = provinces.code AND provinces.area_code = areas.area_code
) as khuvuc, orders,nha_thuoc WHERE orders.store_id = nha_thuoc.store_id AND nha_thuoc.district_id = khuvuc.code AND ". $where ." GROUP BY ma_mien, quy ORDER BY ma_mien,quy";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }    
    $report = array(
      'b' => [],
      't' => [],
      'n' => [],
    );
    foreach($data as $item) {
      switch($item['ma_mien']) {
        case 'b':
          $report['b'][] = $item['doanhthu'];
          break;
        case 't':
          $report['t'][] = $item['doanhthu'];
          break;
        case 'n':
          $report['n'][] = $item['doanhthu'];
          break;
      }
    }
    $chartData = array(
      'labels' => ["Quý 1", "Quý 2", "Quý 3", "Quý 4"],
      'datasets' => array(
        [
          'label' => 'Bắc',
          'data' => $report['b'],
          'backgroundColor' => 'rgba(54, 162, 235, 1)',
        ],
        [
          'label' => 'Trung',
          'data' => $report['t'],
          'backgroundColor' => 'rgba(255, 99, 132, 1)',
        ],
        [
          'label' => 'Nam',
          'data' => $report['n'],
          'backgroundColor' => '#6CBEBF',
        ],
//        [
//          'label' => 'COD',
//          'data' => [0.5,0.7,1],
//          'backgroundColor' => '#F7CF6B',
//        ],
      ),
    );
    $barChart['data'] = $chartData;
    //Chart options 
    $barChart['options'] = array(
      'maintainAspectRatio' => false,
      'showTooltip' => true,
      'title' => array(
        'display' => true,
		'text' => 'Doanh số các miền theo quý năm ' . $year
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
      
    );
    $barChart['width'] = 300;
    $barChart['height'] = 200;
    return $barChart;
  }
  public function reportByAreasMonth($year, $productId, $area) {
    $where = "YEAR(date) = $year";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT code, khuvuc.ma_mien, MONTH(orders.date) as 'thang',sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu' from (
select district.code, areas.area_code as 'ma_mien', areas.name as 'mien' from district,provinces,areas WHERE district.parent_code = provinces.code AND provinces.area_code = areas.area_code
) as khuvuc, orders,nha_thuoc WHERE orders.store_id = nha_thuoc.store_id AND nha_thuoc.district_id = khuvuc.code AND ". $where ." GROUP BY ma_mien, thang ORDER BY ma_mien,thang";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }    
    $report = array(
      'b' => [],
      't' => [],
      'n' => [],
    );
    foreach($data as $item) {
      switch($item['ma_mien']) {
        case 'b':
          $report['b'][] = $item['doanhthu'];
          break;
        case 't':
          $report['t'][] = $item['doanhthu'];
          break;
        case 'n':
          $report['n'][] = $item['doanhthu'];
          break;
      }
    }
    $chartData = array(
      'labels' => [1, 2, 3, 4, 5,6,7,8,9,10,11,12],
      'datasets' => array(
        [
          'label' => 'Bắc',
          'data' => $report['b'],
          'backgroundColor' => 'rgba(54, 162, 235, 1)',
        ],
        [
          'label' => 'Trung',
          'data' => $report['t'],
          'backgroundColor' => 'rgba(255, 99, 132, 1)',
        ],
        [
          'label' => 'Nam',
          'data' => $report['n'],
          'backgroundColor' => '#6CBEBF',
        ],
//        [
//          'label' => 'COD',
//          'data' => [0.5,0.7,1],
//          'backgroundColor' => '#F7CF6B',
//        ],
      ),
    );
    $barChart['data'] = $chartData;
    //Chart options 
    $barChart['options'] = array(
      'maintainAspectRatio' => false,
      'showTooltip' => false,
      'title' => array(
        'display' => true,
		'text' => 'Doanh số các miền theo tháng năm ' . $year
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
      
    );
    $barChart['width'] = 300;
    $barChart['height'] = 200;
    return $barChart;
  }
  public function reportByAreasWeek($year, $productId, $area) {
    $where = "YEAR(date) = $year";
    if($productId != "" && $productId != "all") {
      $where .= " AND <orders.product_id> = '$productId'";
    }
    if($area != "" && $area != "all") {
      $where .= " AND <khuvuc.ma_mien> = '$area'";
    }
    $sql = "SELECT code, khuvuc.ma_mien, WEEK(orders.date) as 'tuan',sum(ROUND(orders.qty * orders.price /1000000,2)) as 'doanhthu' from (
select district.code, areas.area_code as 'ma_mien', areas.name as 'mien' from district,provinces,areas WHERE district.parent_code = provinces.code AND provinces.area_code = areas.area_code
) as khuvuc, orders,nha_thuoc WHERE orders.store_id = nha_thuoc.store_id AND nha_thuoc.district_id = khuvuc.code AND ". $where ." GROUP BY ma_mien, tuan ORDER BY ma_mien,tuan";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }    
//    echo "<pre>";
//    print_r($data);
//    die;
    $report = array(
      'b' => [],
      't' => [],
      'n' => [],
    );
    foreach($data as $item) {
      switch($item['ma_mien']) {
        case 'b':
          $report['b'][] = $item['doanhthu'];
          break;
        case 't':
          $report['t'][] = $item['doanhthu'];
          break;
        case 'n':
          $report['n'][] = $item['doanhthu'];
          break;
      }
    }
    $weekLabels = [];
    for($i = 1; $i <= 52; $i++) {
      $weekLabels[] = $i;
    }
    $chartData = array(
      'labels' => $weekLabels,
      'datasets' => array(
        [
          'label' => 'Bắc',
          'data' => $report['b'],
          'backgroundColor' => 'rgba(54, 162, 235, 1)',
        ],
        [
          'label' => 'Trung',
          'data' => $report['t'],
          'backgroundColor' => 'rgba(255, 99, 132, 1)',
        ],
        [
          'label' => 'Nam',
          'data' => $report['n'],
          'backgroundColor' => '#6CBEBF',
        ],
//        [
//          'label' => 'COD',
//          'data' => [0.5,0.7,1],
//          'backgroundColor' => '#F7CF6B',
//        ],
      ),
    );
    $barChart['data'] = $chartData;
    //Chart options 
    $barChart['options'] = array(
      'maintainAspectRatio' => false,
      'showTooltip' => false,
      'title' => array(
        'display' => true,
		'text' => 'Doanh số các miền theo tuần năm ' . $year
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
      
    );
    $barChart['width'] = 300;
    $barChart['height'] = 200;
    return $barChart;
  }
}