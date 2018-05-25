<?php 
namespace App\Controllers;
use \Slim\Views\PhpRenderer;
use \App\Helper\Data;

class HomeController extends BaseController {
  public function index($request, $response) {
    $data = [];
    return $this->view->render($response, 'home.phtml', $data);
  }
}