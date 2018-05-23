<?php 
namespace App\Model;

class Chart {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
  public function reportByQuarter($year) {
    $data = $this->db->query("SELECT (sum(<qty>*<price>/1000000)) as <doanhthu>, QUARTER(date) as <quy> FROM <orders> WHERE YEAR(date) = $year GROUP BY <quy> ORDER BY <quy>")->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }
    $report = array();
    foreach($data as $quy) {
      $report['labels'][] = "Quý " . $quy['quy'];
      $report['data'][] = $quy['doanhthu'];
    }
    $chartData = array(
      'labels' => $report['labels'],
      'datasets' => array(
        [
          'label' => 'Mục tiêu',
          'data' => [15,20,30,40],
          'backgroundColor' => 'rgba(255, 99, 132, 1)'
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
      'title' => array(
        'display' => true,
		'text' => 'Báo Cáo Doanh Thu Theo Quý (năm '. $year .')'
      ),
      'scales' => [
        'xAxes' => [
          [
            'ticks' => [
              'beginAtZero' => true
            ],
            'scaleLabel' => [
              'display' => true,
              'labelString' => "Biểu đồ doanh thu theo quý năm $year",
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
    $barChart['width'] = 300;
    $barChart['height'] = 300;
    return $barChart;
  }
  public function reportByMonthOfYear($year) {
    $data = $this->db->query("SELECT (sum(<qty>*<price>/1000000)) as <doanhthu>, MONTH(date) as <thang> FROM <orders> WHERE YEAR(date) = $year GROUP BY <thang> ORDER BY <thang>")->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }
    $report = array();
    foreach($data as $quy) {
      $report['labels'][] = $quy['thang'];
      $report['data'][] = $quy['doanhthu'];
    }
    $chartData = array(
      'labels' => $report['labels'],
      'datasets' => array(
        [
          'label' => 'Mục tiêu',
          'data' => [15,20,30,40],
          'backgroundColor' => 'rgba(255, 99, 132, 1)'
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
      'title' => array(
        'display' => true,
		'text' => 'Báo Cáo Doanh Thu Theo Tháng (năm '. $year .')'
      ),
      'scales' => [
        'xAxes' => [
          [
            'ticks' => [
              'beginAtZero' => true
            ],
            'scaleLabel' => [
              'display' => true,
              'labelString' => "Biểu đồ doanh thu theo tháng năm $year",
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
    $barChart['width'] = 300;
    $barChart['height'] = 300;
    return $barChart;
  }
  public function reportByWeekOfYear($year) {
    $data = $this->db->query("SELECT (sum(<qty>*<price>/1000000)) as <doanhthu>, WEEKOFYEAR(date) as <tuan> FROM <orders> WHERE YEAR(date) = $year GROUP BY <tuan> ORDER BY <tuan>")->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }
//    echo "<pre>";
//    print_r($data);
//    die();
    $report = array();
    foreach($data as $quy) {
      $report['labels'][] = $quy['tuan'];
      $report['data'][] = $quy['doanhthu'];
    }
    $chartData = array(
      'labels' => $report['labels'],
      'datasets' => array(
        [
          'label' => 'Mục tiêu',
          'data' => [15,20,30,40],
          'backgroundColor' => 'rgba(255, 99, 132, 1)'
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
      'title' => array(
        'display' => true,
		'text' => 'Báo Cáo Doanh Thu Theo Tuần (năm '. $year .')'
      ),
      'scales' => [
        'xAxes' => [
          [
            'ticks' => [
              'beginAtZero' => true
            ],
            'scaleLabel' => [
              'display' => true,
              'labelString' => "Biểu đồ doanh thu theo tuần năm $year",
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
    $barChart['width'] = 300;
    $barChart['height'] = 300;
    return $barChart;
  }
  public function reportByProducts($year) {
    $data = $this->db->query("SELECT (sum(<orders.qty>*<products.price>/1000000)) as <doanhthu>, <products.name> FROM <orders>,<products> WHERE <orders.product_id> = <products.product_id> AND YEAR(<orders.date>) = $year GROUP BY <products.name> ORDER BY <doanhthu>")->fetchAll(\PDO::FETCH_ASSOC);
    if(empty($data)) {
      return array(
        'status' => 'error', 
        'message' => 'Empty data!'
      );
    }
//    echo "<pre>";
//    print_r($data);
//    die();
    $report = array();
    foreach($data as $product) {
      $report['labels'][] = $product['name'];
      $report['data'][] = $product['doanhthu'];
    }
    $chartData = array(
      'type' => 'horizontalBar',
      'labels' => $report['labels'],
      'datasets' => array(
        [
          'label' => 'Mục tiêu',
          'data' => [15,20,30,40],
          'backgroundColor' => 'rgba(255, 99, 132, 1)'
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
      'title' => array(
        'display' => true,
		'text' => 'Báo Cáo Doanh Thu Tất Cả Sản Phẩm (năm '. $year .')',
        'fontStyle' => 'bold',
        'fontColor' => '#ccc'
      ),
      'scales' => [
        'yAxes' => [
          [
            'scaleLabel' => [
              'display' => true,
              'labelString' => "Sản phẩm",
              'fontStyle' => 'bold',
              'fontColor' => '#ccc'
            ]
          ]
        ],
        'xAxes' => [
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
    $barChart['width'] = 300;
    $barChart['height'] = 300;
    return $barChart;
  }
}