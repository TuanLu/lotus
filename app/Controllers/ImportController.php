<?php 
namespace App\Controllers;

class ImportController extends BaseController {
  const MAX_UPLOAD_FILESIZE = 20000000;//20M
  public function index($request, $response) {
    $data = [
      
    ];
    return $this->view->render($response, 'import.phtml', $data);
  }  
  function upload($request, $response) {
    $resStatus = array(
      'status' => 'error',
      'message' => 'Something went wrong! Can not upload file to server!'
    );
    //Directory of image. Magento will different with Wordpress
    $target_dir = $this->baseDir() . '/resource/upload/';
    $filename = basename($_FILES["filename"]["name"]);
    //Try to upload image without wordpress function
    
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    $fileExt = pathinfo($target_file,PATHINFO_EXTENSION);
    // Allow certain file formats
    if($fileExt != "xlsx" && $fileExt != "xls") {
        $resStatus['message'] = "Xin lỗi, chỉ cho phép upload file excel có đuôi .xlsx, xls";
        $uploadOk = 0;
    }
    // Check if file is a actual file or fake file
    if(isset($_POST["submit"])) {
        $check = filesize($_FILES["filename"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $resStatus['message'] = "File is invalid.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        //$resStatus['message'] = "Sorry, file already exists.";
        $filename = time() . '_' . $filename;
        $target_file = $target_dir . $filename;
        //If file already exists then rename the file and allow upload normally
        $uploadOk = 1;
    }
    // Check file size
    if ($_FILES["filename"]["size"] > self::MAX_UPLOAD_FILESIZE) {
        $resStatus['message'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
      if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
          //Try to read excel
          try {
            $helper = new \App\Helper\Data();
            $data = $helper->readExcel($target_file);
            $resStatus['status'] = 'success';
            $resStatus['message'] = "The file ". $filename. " has been uploaded.";
            $resStatus['data'] = $data;
          }catch(Exception $e) {
            $resStatus['message'] = "Xin lỗi! Server không thể đọc được file excel này: $filename!";
          }
      } else {
          $resStatus['message'] = "Sorry, there was an error uploading your file.";
      }
    }
    $response->getBody()->write(json_encode($resStatus));
    return $response->withHeader('Content-type', 'application/json');
  }
  public function readExcel($path) {
    if(file_exists($path)) {
      $reader = new Xlsx();
      $spreadsheet = $reader->load($path);
      $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
      //Remove title row of excel
      unset($sheetData[1]);
      return $sheetData;
    }
    return [];
  }
}