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
$app->post('/token', 'UserController:token');
$app->get('/fetchRoles', 'UserController:fetchRoles');//Per User
$app->get('/fetchAllRoles', 'UserController:fetchAllRoles');// To assign to user

$app->get('/users/fetchUsers', 'UserController:fetchUsers');
$app->post('/users/updateUser', 'UserController:updateUser');
$app->get('/users/deleteUser/{id}', 'UserController:deleteUser');

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

//Product routers
$app->get('/product/fetchProducts', 'ProductController:fetchProducts');
$app->post('/product/updateProduct', 'ProductController:updateProduct');
$app->get('/product/deleteProduct/{id}', 'ProductController:deleteProduct');
//TDV routers
$app->get('/tdv/fetchTdvs', 'TdvController:fetchTdvs');
$app->post('/tdv/updateTdv', 'TdvController:updateTdv');
$app->get('/tdv/deleteTdv/{id}', 'TdvController:deleteTdv');
