<?php 
namespace App\Helper;

class Roles {
  static function getRoles() {
    return [
      'full' => [
        'label' => 'Admin', 
        'icon' => 'solution',
        'path' => 'full'
      ],
      'import' => [
        'label' => 'Nhập dữ liệu', 
        'icon' => 'user',
        'path' => 'import'
      ],
      'chart' => [
        'label' => 'Xem báo cáo', 
        'icon' => 'chart',
        'path' => 'chart'
      ],
    ];
  }
}