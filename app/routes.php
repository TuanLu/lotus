<?php
// PSR 7 standard.
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// $app->get('/', function (Request $request, Response $response, $args) {
//   $data['template_data'] = 'Lotus.App.Slim';
//   //$this->logger->addInfo('You open home router');
//   // Columns to select.
//   $columns = [
//     'uuid',
//     'name',
//     'created_on',
//     'updated_on',
//   ];

//   // Get user.
//   // https://medoo.in/api/get
//   $data = $this->db->get('users', $columns, [
//       "name" => "Lu Tuan"
//   ]);
//   $this->renderer->render($response, "home.phtml", $data);
// });

$app->get('/test', 'HomeController:index');
$app->get('/import', 'ImportController:index');
$app->post('/upload', 'ImportController:upload');
$app->get('/report', 'ReportController:index');
$app->get('/', 'ReportController:chart');
$app->get('/product', 'ReportController:product');
$app->get('/provinces', 'ReportByProvinceController:provinces');
$app->get('/reportbyprovince', 'ReportByProvinceController:index');
$app->get('/stores', 'StoreController:index');
$app->get('/storeslocation', 'StoreController:location');
$app->post('/updatestore', 'StoreController:updatestore');
$app->get('/deletestore/{id}', 'StoreController:deletestore');//DELETE not allowed in http request
$app->get('/orders', 'OrderController:index');
$app->get('/deleteorder/{id}', 'OrderController:deleteorder');
$app->get('/importOrderData', 'OrderController:importOrderData');
$app->post('/addorders', 'OrderController:addorders');
$app->get('/plan', 'PlanController:index');
$app->post('/updateplan', 'PlanController:updateplan');
