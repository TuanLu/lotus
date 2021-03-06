<?php
namespace App\Controllers;
use \Medoo\Medoo;
use \Monolog\Logger;
//use \Ramsey\Uuid\Uuid;

class TdvController extends BaseController
{
	private $tableName = 'tdv';
 
	public function fetchTdvs($request){
		//$this->logger->addInfo('Request Npp path');
		$rsData = array(
			'status' => self::ERROR_STATUS,
			'message' => 'Chưa có dữ liệu từ hệ thống!'
		);
		// Columns to select.
		$columns = [
				'id',
				'ma_tdv',
				'name',
				'address',
				'phone',
				'company'
		];
		$collection = $this->db->select($this->tableName, $columns, [
			"status" => 1
		]);
		if(!empty($collection)) {
			$rsData['status'] = self::SUCCESS_STATUS;
			$rsData['message'] = 'Dữ liệu đã được load!';
			$rsData['data'] = $collection;
		}
		echo json_encode($rsData);
	}
	public function updateTdv($request, $response)
	{
		$rsData = array(
			'status' => self::ERROR_STATUS,
			'message' => 'Xin lỗi! Dữ liệu chưa được cập nhật thành công!'
		);
		// Get params and validate them here.
		//$params = $request->getParams();
		$id = $request->getParam('id');
		$maTdv = $request->getParam('ma_tdv');
		$name = $request->getParam('name');
		$address = $request->getParam('address');
		$phone = $request->getParam('phone');
		$company = $request->getParam('company');
		if(!$id) {
			//Insert new data to db
			if(!$maTdv) {
				$rsData['message'] = 'Mã trình dược viên không được để trống!';
				echo json_encode($rsData);
				die;
			}
			if(!$name) {
				$rsData['message'] = 'Tên trình dược viên không được để trống!';
				echo json_encode($rsData);
				die;
			}
			$date = new \DateTime();
			$itemData = [
				'ma_tdv' => $maTdv,
				'name' => $name,
				'address' => $address,
				'phone' => $phone,
				'company' => $company,
				'create_on' => $date->format('Y-m-d H:i:s'),
			];
			$selectColumns = ['id', 'ma_tdv'];
			$where = ['ma_tdv' => $itemData['ma_tdv']];
			$data = $this->db->select($this->tableName, $selectColumns, $where);
			if(!empty($data)) {
				$rsData['message'] = "Mã trình dược viên [". $itemData['ma_tdv'] ."] đã tồn tại: ";
				echo json_encode($rsData);exit;
			}
			$result = $this->db->insert($this->tableName, $itemData);
			
			if($result->rowCount()) {
				$rsData['status'] = 'success';
				$rsData['message'] = 'Đã thêm trình dược viên mới thành công!';
				$tdvId = $this->db->id();
				$rsData['data'] = ['id' => $tdvId];
			} else {
				$rsData['message'] = 'Dữ liệu chưa được cập nhật vào cơ sở dữ liệu!';
			}
		} else {
			//update data base on $id
			$date = new \DateTime();
			$itemData = [
				'ma_tdv' => $maTdv,
				'name' => $name,
				'address' => $address,
				'phone' => $phone,
				'company' => $company,
				'update_on' => $date->format('Y-m-d H:i:s'),
			];
			$result = $this->db->update($this->tableName, $itemData, ['id' => $id]);
			if($result->rowCount()) {
				//$this->superLog('Update NPP', $itemData);
				$rsData['status'] = self::SUCCESS_STATUS;
				$rsData['message'] = 'Dữ liệu đã được cập nhật vào hệ thống!';
			} else {
				$rsData['message'] = 'Dữ liệu chưa được cập nhật vào hệ thống! Có thể do bị trùng Mã trình dược viên!';
			}
			
		}
		echo json_encode($rsData);
	}

	public function deleteTdv($request, $response, $args){
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
				$rsData['message'] = 'Đã xoá trình dược viên khỏi hệ thống!';
				$rsData['data'] = $id;
			}
		} else {
			$rsData['message'] = 'ID trống, nên không xoá được dữ liệu!';
		}
		echo json_encode($rsData);
	}
}
