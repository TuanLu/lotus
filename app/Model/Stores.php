<?php 
namespace App\Model;
//use \Ramsey\Uuid\Uuid;

class Stores {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
  public function getStores() {
    $sql = "SELECT store_id, nha_thuoc.name, phone, owner, address, district_id, district.name as 'huyen',provinces.code as 'province_id',areas.area_code as 'area_id'  FROM nha_thuoc LEFT JOIN district ON nha_thuoc.district_id = district.code LEFT JOIN provinces ON district.parent_code = provinces.code LEFT JOIN areas ON provinces.area_code = areas.area_code";
    $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    return $data;
  }
  public function getLocation() {
    $provincesSQL = "SELECT code, name, area_code FROM provinces";
    $districtSQL = "SELECT code, name, parent_code FROM district";
    $provincesData = $this->db->query($provincesSQL)->fetchAll(\PDO::FETCH_ASSOC);
    $districtData = $this->db->query($districtSQL)->fetchAll(\PDO::FETCH_ASSOC);
    return array(
      'provinces' => $provincesData,
      'district' => $districtData
    );
  }
  public function updateStore($data) {
    if(isset($data['store_id']) && $data['store_id'] != "") {
      //Update mode
      $result = $this->db->update('nha_thuoc', [
        'store_id' => $data['store_id'],
        'name' => $data['name'],
        'address' => $data['address'],
        'phone' => $data['phone'],
        'owner' => $data['owner'],
        'district_id' => $data['district_id'],
      ], [
        'store_id' => $data['store_id']
      ]);
      return $result->rowCount();
    } else {
      //General New Store ID
      //$uuid1 = Uuid::uuid1();
      //$uuid = $uuid1->toString();
      $lastIdArr = $this->db->select('store_last_id', ['number','prefix'], ['id' => 1]);
      $lastStoreNumber = $lastIdArr[0]['number'];
      //$lastStoreNumber += 1;
      $storeIdPrefix = $lastIdArr[0]['prefix'];
      $storeData = [
        'store_id' => $storeIdPrefix . $lastStoreNumber,
        'name' => $data['name'],
        'address' => $data['address'],
        'phone' => $data['phone'],
        'owner' => $data['owner'],
        'district_id' => $data['district_id'],
      ];
      $result = $this->db->insert('nha_thuoc', $storeData);
      if($result->rowCount()) {
        $this->db->update('store_last_id', ['number' => $lastStoreNumber + 1],['id' => 1]);
        return $storeData;
      }
    }
  }
  public function deleteStore($storeId) {
    $result = $this->db->delete('nha_thuoc', [
      'store_id' => $storeId
    ]);
    return $result->rowCount();
  }
  public function checkExistsStores($inString) {
    $sql = "SELECT title, store_id FROM (SELECT store_id, REPLACE(LOWER(CONCAT(name,address)), ' ','') AS title  FROM nha_thuoc) as stores WHERE title IN ($inString)";
    return $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
  }
}