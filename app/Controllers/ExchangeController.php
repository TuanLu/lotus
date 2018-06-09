<?php
namespace App\Controllers;
use \Medoo\Medoo;
use \Monolog\Logger;
//use \Ramsey\Uuid\Uuid;

class ExchangeController extends BaseController
{
	private $tableName = 'exchange';
 
	public function fetchExchange($request){
		//$this->logger->addInfo('Request Npp path');
		$rsData = array(
			'status' => self::ERROR_STATUS,
			'message' => 'Chưa có dữ liệu từ hệ thống!'
		);
		// Columns to select.
		$columns = [
				'id',
				'product_id',
				'vi',
				'lo',
				'siro'
		];
		$collection = $this->db->select($this->tableName, $columns, [
			"status" => '1'
		]);
		if(!empty($collection)) {
			$rsData['status'] = self::SUCCESS_STATUS;
			$rsData['message'] = 'Dữ liệu đã được load!';
			$rsData['data'] = $collection;
		}
		echo json_encode($rsData);
	}
	public function updateExchange($request, $response)
	{
		$rsData = array(
			'status' => self::ERROR_STATUS,
			'message' => 'Xin lỗi! Dữ liệu chưa được cập nhật thành công!'
		);
		// Get params and validate them here.
		//$params = $request->getParams();
		$id = $request->getParam('id');
		$maSP = $request->getParam('product_id');
		$lo = $request->getParam('lo');
		$vi = $request->getParam('vi');
		$siro = $request->getParam('siro');
		if(!$id) {
			//Insert new data to db
			if(!$maSP) {
				$rsData['message'] = 'Mã sản phẩm không được để trống!';
				echo json_encode($rsData);
				die;
			}
			$date = new \DateTime();
			$itemData = [
				'product_id' => $maSP,
				'lo' => $lo,
				'vi' => $vi,
				'siro' => $siro,
				'create_on' => $date->format('Y-m-d H:i:s'),
			];
			//Kiểm tra nhà phân phối đã tồn tại chưa 
			$selectColumns = ['id', 'product_id'];
			$where = ['product_id' => $itemData['product_id']];
			$data = $this->db->select($this->tableName, $selectColumns, $where);
			if(!empty($data)) {
				$rsData['message'] = "Mã sản phẩm [". $itemData['product_id'] ."] đã tồn tại: ";
				echo json_encode($rsData);exit;
			}
			$result = $this->db->insert($this->tableName, $itemData);
			
			if($result->rowCount()) {
				$rsData['status'] = 'success';
				$rsData['message'] = 'Đã thêm đơn vị tính mới thành công!';
				$tdvId = $this->db->id();
				$rsData['data'] = ['id' => $tdvId];
			} else {
				$rsData['message'] = 'Dữ liệu chưa được cập nhật vào cơ sở dữ liệu!';
			}
		} else {
			//update data base on $id
			$date = new \DateTime();
			$itemData = [
				'product_id' => $maSP,
				'lo' => $lo,
				'vi' => $vi,
				'siro' => $siro,
				'update_on' => $date->format('Y-m-d H:i:s'),
			];
			$result = $this->db->update($this->tableName, $itemData, ['id' => $id]);
			if($result->rowCount()) {
				//$this->superLog('Update NPP', $itemData);
				$rsData['status'] = self::SUCCESS_STATUS;
				$rsData['message'] = 'Dữ liệu đã được cập nhật vào hệ thống!';
			} else {
				$rsData['message'] = 'Dữ liệu chưa được cập nhật vào hệ thống! Có thể do bị trùng Mã đơn vị tính!';
			}
			
		}
		echo json_encode($rsData);
	}

	public function deleteExchange($request, $response, $args){
		$rsData = array(
			'status' => self::ERROR_STATUS,
			'message' => 'Dữ liệu chưa được xoá thành công!'
		);
		// Get params and validate them here.
		$id = isset(	$args['id']) ? $args['id'] : '';
		if($id != "") {
			$result = $this->db->update($this->tableName,['status' => 2], ['id' => $id]);
			if($result->rowCount()) {
				//$this->superLog('Delete NPP', $id);
				$rsData['status'] = self::SUCCESS_STATUS;
				$rsData['message'] = 'Đã xoá đơn vị tính khỏi hệ thống!';
				$rsData['data'] = $id;
			}
		} else {
			$rsData['message'] = 'ID trống, nên không xoá được dữ liệu!';
		}
		echo json_encode($rsData);
	}
}
