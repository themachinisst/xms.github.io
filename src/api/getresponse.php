<?php
require_once "../config.php";
error_reporting(0);
$json=file_get_contents('php://input');
$Id = json_decode($json,true)['Id'];
$decode_array = json_decode($json,true)['responses'];
  
$statusResponse=Array();
$statusResp="success";

if(!empty($decode_array) && isset($Id)){

    // echo $Id;
    // print_r($decode_array);
// if(isset($_POST['Id']) && isset($_POST['QuestionId']) && isset($_POST['Response']) ){
    foreach($decode_array as $response_array){
        $QuestionId = $response_array['QuestionId'];
        $Response = $response_array['Response'];    
        
        $sql = "INSERT INTO responses(Id, QuestionId, Response) VALUES (?,?,?)";

        if($stmt = $mysqli->prepare($sql)){
            
            // $Id = $_POST['Id'];
            // $QuestionId = $_POST['QuestionId'];
            // $Response = $_POST['Response'];
            $stmt->bind_param("iis", $Id, $QuestionId, $Response);
            if($stmt -> execute()){
                //if query works successfully return no  
            }else{

                $statusResp="failed";
                response("Failure");
                break;
            }
        }
    }
    response($statusResp);
}else{
    response("Missing parameters.");
}

function response($signoffMessage)
{

	$response['res'] = $signoffMessage;
	$json_response = json_encode($response);
	echo $json_response;
}
?>
