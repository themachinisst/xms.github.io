<?php
require_once "../config.php";


function returnRadioBtnOptions($mysqli, $QuestionId){
    $radioId = "";
    $optionText = "";
    $sql = 'SELECT RadioId, OptionText FROM RadioBtnOptions WHERE Status = ? AND QuestionId = ?';
    $options = array();
    if($stmt = $mysqli->prepare($sql)){
        $status = 1;
        $temp = array();
        $stmt->bind_param("is",  $status, $QuestionId);
        if($stmt -> execute()){
            $stmt->store_result();
            $stmt -> bind_result($radioId, $optionText);
            if($stmt -> num_rows()>0){
                while($stmt -> fetch()) {
                    $temp = array("OptionId"=>$radioId, "OptionText"=>$optionText);
                    array_push($options, $temp);
                }
                return $options;
            }else{
                return $options;
            }
        }else{
            return $options = "Fail".mysqli_error($mysqli);
        }
    }else{
        return $options = "Fail".mysqli_error($mysqli);
    }

}


function returnDropDownOptions($mysqli, $QuestionId){
    $optionId = "";
    $optionText = "";
    $sql = 'SELECT OptionId, OptionText FROM DropDownOptions WHERE Status = ? AND QuestionId = ?';
    $options = array();
    if($stmt = $mysqli->prepare($sql)){
        $status = 1;
        $temp = array();
        $stmt->bind_param("is",  $status, $QuestionId);
        if($stmt -> execute()){
            $stmt->store_result();
            $stmt -> bind_result($optionId, $optionText);
            if($stmt -> num_rows()>0){
                while($stmt -> fetch()) {
                    $temp = array("OptionId"=>$optionId, "OptionText"=>$optionText);
                    array_push($options, $temp);
                    
                }
                return $options;
            }else{
                return $options;
            }
        }else{
            return $options = "Fail".mysqli_error($mysqli);
        }
    }else{
        return $options = "Fail".mysqli_error($mysqli);
    }

}

if(isset($_POST['Requirement'])){
    $sql = 'SELECT QuestionId, Question, ResponseType FROM masterquestions WHERE (Requirement = ? OR Requirement = ? ) AND Status = ?';

    if($stmt = $mysqli->prepare($sql)){
        
        $requirement = $_POST['Requirement'];
        $generalRequirement = 'General';
        $status=1;
        $stmt->bind_param("ssi", $requirement, $generalRequirement, $status);
        if($stmt -> execute()){
            //store results
            $stmt->store_result();
            $stmt -> bind_result($questionId, $question, $responseType);
            $questionarr = array();
            if($stmt -> num_rows()>0)
            {
                while($stmt -> fetch()) {
                    if($responseType == 'dropdown'){
                        $options = array(returnDropDownOptions($mysqli, $questionId));
                        $temp = array("QuestionId"=>$questionId, "Question"=>$question, "ResponseType"=>$responseType, "Options"=>$options);
                    }else if($responseType == 'radiobutton'){
                        $options = array(returnRadioBtnOptions($mysqli, $questionId));
                        $temp = array("QuestionId"=>$questionId, "Question"=>$question, "ResponseType"=>$responseType, "Options"=>$options);
                    }else{
                        $temp = array("QuestionId"=>$questionId, "Question"=>$question, "ResponseType"=>$responseType);
                    }
                    array_push($questionarr, $temp);
                }
                response($questionarr);
            }else{
                response($questionarr);
            }
        }else{
            response("False".mysqli_error($mysqli));
        }
        //close statement
        $stmt->close();
    }else{
        response("False".mysqli_error($mysqli));
    }
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