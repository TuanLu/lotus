<?php 
namespace App\Controllers;
use \Slim\Views\PhpRenderer;

class HomeController extends BaseController {
  public function index($request, $response) {
    $data = $request->getParams();
    return $this->view->render($response, 'home.phtml', $data);
  }
}