<?php
require_once "../config.php";

if(isset($_POST['Requirement'])){
    $sql = 'SELECT QuestionId, Question FROM masterquestions WHERE (Requirement = ? OR Requirement = ? ) AND Status = ?';

    if($stmt = $mysqli->prepare($sql)){
        
        $requirement = $_POST['Requirement'];
        $generalRequirement = 'general';
        $status=1;
        $stmt->bind_param("ssi", $requirement, $generalRequirement, $status);
        if($stmt -> execute()){
            //store results
            $stmt->store_result();
            $stmt -> bind_result($questionId, $question);
            $questionarr = array();
            if($stmt -> num_rows()>0)
            {
                while($stmt -> fetch()) {
                    $temp = array("QuestionId"=>$questionId, "Question"=>$question);
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