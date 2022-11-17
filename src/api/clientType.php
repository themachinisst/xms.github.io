<?php


  require_once "../config.php";
 // error_reporting(0);
  $files = glob("./pdf/include/*.php");
//foreach($files as $file) include_once($file);

  $json=file_get_contents('php://input');
 //$Id = json_decode($json,true)['Id'];
//$decode_array = json_decode($json,true)['responses'];


  
  if(isset($_POST['Id']) && isset($_POST['Organization']) && isset($_POST['OrganizationName']) && !empty($_POST['OrganizationName']) && isset($_POST['SubAgency']) && isset($_POST['BrandName']) && !empty($_POST['BrandName'])){
  
    $sql_client = "UPDATE login SET Organization = ?, OrganizationName =?, SubAgency = ?,BrandName = ? WHERE Id=?";
    
    if($stmt = $mysqli->prepare($sql_client)){
      
            $Id = $_POST['Id'];
            $Organization = $_POST['Organization'];
            $OrganizationName = $_POST['OrganizationName'];
            $SubAgency = $_POST['SubAgency'];
            $BrandName = $_POST['BrandName'];
            
            
        
      $stmt->bind_param("ssssi", $Organization, $OrganizationName, $SubAgency, $BrandName, $Id);
       
      if($stmt -> execute()){ 
        
       response("success");
       echo $BrandName;
         //if query works successfully return no  
      }else{

      $statusResp="failed";
      response("Failure");
        
      }
    }else{
    response($statusResp);
    }
    // $sucess="success";
    // echo $sucess;
  }else{
    //echo  $BrandName;
  response("Missing parameters");
  }

  function response($signoffMessage){  
  $response['res'] = $signoffMessage;
	$json_response = json_encode($response);
  echo $json_response;
  }
?>