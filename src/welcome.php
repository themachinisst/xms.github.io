<?php

//Initialize the session
error_reporting(0);
session_start();
include_once('../src/api/jwt.php');

//Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$checked = "";
$jsonResponse = "0";

if(isset($_POST['ClientTypes'])){
    $checked = $_POST['ClientTypes'];

    $url = 'http://localhost/xms/src/api/getquestion.php';
    $postData = array("Requirement" => $checked);
    $jsonResponse = rest_call('POST',$url, $postData,'multipart/form-data',"Bearer ".$_COOKIE['kpmg-access']);
    $response = json_decode($jsonResponse, true);

    $number_question = count($response['res']);
    $_SESSION["questions"] = $response['res'];
}

if(isset($_POST['responseSubmit'])){
    $Id = $_SESSION["id"];
    $tempRes = array();
    $temp1 = array();
    $number_question = count($_SESSION["questions"]);
    $question = $_SESSION["questions"];
     
    $urlRes = 'http://localhost/xms/src/api/getresponse.php';
    for($i=0;$i<$number_question;$i++){
        
        $temp = array("QuestionId"=>$question[$i]['QuestionId'], "Response"=>$_POST['response'.$i]);
        array_push($temp1,$temp);
    }
    
    $postDataRes = json_encode(array("Id"=>$Id, "responses"=>$temp1));
    $jsonResponseRes = rest_call('POST',$urlRes, $postDataRes,'multipart/form-data',"Bearer ".$_COOKIE['kpmg-access']);
    $responseRes =  json_decode($jsonResponseRes, true)['res'];
    if($responseRes === 'success'){
        
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script type="text/javascript" src="./js/welcome.js"></script>
    <link rel="stylesheet" href="../style/welcome_style.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hello, <b><?php echo htmlspecialchars($_SESSION["Email"]); ?></b></h1>
    <!-- Requirement Type  -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"  name="frm" id="frm" class = "frm">
        <div class="form-group">
            <br/>
            <ul>
                <li class = "ClientTypes">
                    <input type="radio" name="ClientTypes"  <?php if ($checked == 'Content') { ?>checked='checked' <?php } ?> value="Content" id="RequirementContent" onChange="autoSubmit();"/>
                    <label class = "ClientTypesRadioBtns" >Content</label>
                    <input type="radio" name="ClientTypes"   <?php if ($checked == 'Digital') { ?>checked='checked' <?php } ?> value="Digital" id="RequirementDigital" onChange="autoSubmit();"/>
                    <label class = "ClientTypesRadioBtns" >Digital</label>
                    <input type="radio" name="ClientTypes"   <?php if ($checked == 'On Ground') { ?>checked='checked' <?php } ?>  value="On Ground" id="RequirementOnGround" onChange="autoSubmit();"/>
                    <label class = "ClientTypesRadioBtns" >On Ground</label>
                    <input type="radio" name="ClientTypes"   <?php if ($checked == 'Hybrid') { ?>checked='checked' <?php } ?>  value="Hybrid" id="RequirementHybrid" onChange="autoSubmit();"/>
                    <label class = "ClientTypesRadioBtns" >Hybrid</label>
                </li>
            </ul>
        </div>
    </form>
     <!-- Requirement Type  -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class = "questionsForm" name="questionsForm" id = "questionsForm">
        <!-- Questions will be added from js-->
        <input type = "hidden" value = "<?php if ($checked == 'Content') { ?>checked='checked' <?php } ?>">
    </form>
    <!-- Requirement Type  -->
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out</a>
    </p>
</body>

<script>

    let RequirementContent = document.getElementById('RequirementContent');
    let RequirementDigital = document.getElementById('RequirementDigital');
    let RequirementOnGround = document.getElementById('RequirementOnGround');
    let RequirementHybrid = document.getElementById('RequirementHybrid');


    if(RequirementContent.checked || RequirementDigital.checked || RequirementOnGround.checked || RequirementHybrid.checked){
        let javaScriptVar = <?php echo $jsonResponse; ?>;
        // console.log(javaScriptVar.res[0].QuestionId);
        for(var i=0; i<javaScriptVar.res.length; i++){
            document.getElementById('questionsForm').innerHTML += `
                <div  class="form-group" id = "question`+(javaScriptVar.res[i].QuestionId)+`">
                    <p>`+(i+1)+`) `+javaScriptVar.res[i].Question+`</p>
                    <input type = "text" name = "response`+(i)+`"  id= "response`+(i)+`" required/>
                </div>
            `;
        }

        document.getElementById('questionsForm').innerHTML +=`
            <div class="form-group">
                <input type = "checkbox"  name="terms" value="1" required> 
                I Agree  
                <a href="javascript:void(0);" class = "TermsConditionsText" id="mpopupLink">
                    NDA
                </a>
                <span class="invalid-feedback"><?php echo $terms_err; ?></span>
            </div>
            <input type="submit" class="btn btn-primary" name = "responseSubmit" value="Submit">
        `;
    }

</script>
</html>


