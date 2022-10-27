<?php


require_once "../config.php";

$sql = 'SELECT QuestionId, Question FROM masterquestions WHERE Status = ?';

if($stmt = $mysqli->prepare($sql)){
    
    $status=1;
    $stmt->bind_param("i", $status);
    if($stmt -> execute()){
        //store results
        $stmt->store_result();
        $stmt -> bind_result($questionId, $question);

        if($stmt -> num_rows()>0)
        {
            $stmt -> fetch();
            $quesionarr = array("QuestionId"=>$questionId, "Question"=>$question);
            response($quesionarr);
        }else{
            response("False","No entries", NULL);
        }
    }else{
        response("False","Failed", NULL);
    }
    //close statement
    $stmt->close();
}else{
    response("False");
}


function response($signoffMessage)
{

	$response['res'] = $signoffMessage;
	$json_response = json_encode($response);
	echo $json_response;
}

?>