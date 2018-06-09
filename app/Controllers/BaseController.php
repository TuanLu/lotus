<?php 
namespace App\Controllers;

class BaseController {
  const ERROR_STATUS = 'error';
	const SUCCESS_STATUS = 'success';
  protected $container;
  public function __construct($container) {
    $this->container = $container;
  }
  public function __get($prop) {
    if($this->container->{$prop}) {
      return $this->container->{$prop};
    }
  }
  public function baseDir() {
    $dir = __DIR__;
    return str_replace('app/Controllers', '', $dir);
  }
}