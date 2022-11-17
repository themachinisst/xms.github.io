<?php

//Initialize the session
error_reporting(0);
session_start();
include_once('../src/api/jwt.php');
include('./config.php');

//Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$checked = "";
$jsonResponse = "0";
$Id = $_SESSION["id"];



if(isset($_POST['RequirementTypes'])){
    $checked = $_POST['RequirementTypes'];

    $url = $basepath.'api/getquestion.php';
    $postData = array("Requirement" => $checked);
    $jsonResponse = rest_call('POST',$url, $postData,'multipart/form-data',"Bearer ".$_COOKIE['kpmg-access']);
    $response = json_decode($jsonResponse, true);

    $number_question = count($response['res']);
    // print_r($response['res']);
    $_SESSION["questions"] = $response['res'];
}

if(isset($_POST['responseSubmit'])){

    $tempRes = array();
    $temp1 = array();
    $number_question = count($_SESSION["questions"]);
    $question = $_SESSION["questions"];

     
    $urlRes = $basepath.'api/getresponse.php';
    for($i=0;$i<$number_question;$i++){

        // for file upload 
        if($question[$i]['ResponseType'] === "file" && empty($_POST['response'.$i])){
            continue;
        }else if($question[$i]['ResponseType'] === "file" && isset($_POST['response'.$i]) && !empty($_POST['response'.$i])){
            $formRespones = $basepath.$Id.'/'.$_POST['response'.$i];
        }else if($question[$i]['ResponseType'] === "radiobutton" && isset($_POST['response'.$i])){
            echo "in";
            print_r($_POST['response'.$i]);
        }else{
            $formRespones = $_POST['response'.$i];
        }

        $temp = array("QuestionId"=>$question[$i]['QuestionId'], "Response"=>$formRespones);
        array_push($temp1,$temp);
    }
    
    $postDataRes = json_encode(array("Id"=>$Id, "responses"=>$temp1));
    // print_r($postDataRes);
    $jsonResponseRes = rest_call('POST',$urlRes, $postDataRes,'multipart/form-data',"Bearer ".$_COOKIE['kpmg-access']);
    $responseRes =  json_decode($jsonResponseRes, true)['res'];
    if($responseRes === 'success'){
        // reduirect to thank you page
        
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/577845f6a5.js" 
        crossorigin="anonymous">
    </script>
  
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="./js/welcome.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../style/welcome_style.css">
    <link rel="stylesheet" href="../style/main.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>

</head>

<!-- for file upload api - START  -->
<script>
if($("input[name='RequirementTypes']").prop("checked")){
    document.getElementById("responseSubmit").addEventListener("click", function(event){
            $('#responseSubmit').on('click', function (e) {
                // console.log('<?php echo $Id;?>');
                let files = new FormData(), // you can consider this as 'data bag'
                url = 'http://localhost/xms/src/api/uploadfile.php';
                
                files.append('file', $('#file')[0].files[0]); // append selected file to the bag named 'file'
                files.append('Id', '<?php echo $Id ?>');
                $.ajax({
                    type: 'post',
                    url: url,
                    processData: false,
                    contentType: false,
                    data: files,
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
            event.preventDefault();
        });
    }
</script>
<!-- for file upload api - START  -->
<body>
    <div class = "parent">
        <!-- Left direction block - START -->
        <div class = "left-child">
            <img src="../assets/left_arrow.png" class = "left" id = "left"/>
        </div>
        <!-- Left direction block - END -->

        <!-- Centre Block - START-->
        <div class = "child">

            <!-- Requirement Type  -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"  name="frm1" id="frm1" class = "frm1">
                <div class="form-group" >
                    <br/>
                    <p class="MainTitle"><b>Alright !  <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b> let's see what you got</b></p>
                    <p class="Text">Select your campaign activation platform</p>
                    <br/>
                    <br/>
                    <div class="btn-group-toggle" data-toggle="buttons">
                        <label class="btn radiobtn">
                            Content
                            <input type="radio" name="RequirementTypes"  <?php if ($checked == 'Content') { ?>checked='checked' <?php } ?> value="Content" id="RequirementContent" onClick="autoSubmit(frm1);"/>
                        </label>
                        
                        <label class="btn radiobtn">
                            <input type="radio" name="RequirementTypes"  <?php if ($checked == 'Digital') { ?>checked='checked' <?php } ?> value="Digital" id="RequirementDigital" onClick="autoSubmit(frm1);"/>
                            Digital
                        </label>
                    
                        <label class="btn radiobtn">
                            <input type="radio" name="RequirementTypes"  <?php if ($checked == 'On Ground') { ?>checked='checked' <?php } ?> value="On Ground" id="RequirementOnGround" onClick="autoSubmit(frm1);"/>
                            On Ground
                        </label>
                        
                        <label class="btn radiobtn">
                            <input type="radio" name="RequirementTypes"  <?php if ($checked == 'Hybrid') { ?>checked='checked' <?php } ?> value="Hybrid" id="RequirementHybrid" onClick="autoSubmit(frm1);"/>
                            Hybrid
                        </label>
                        
                    </div>
                </div>
            </form>
            <!-- Requirement Type  -->

            <!-- Questions Type  -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class = "questionsForm" name="questionsForm" id = "questionsForm">
                <!-- Questions will be added from js-->
                <input type = "hidden" value = "<?php if ($checked == 'Content') { ?>checked='checked' <?php } ?>">
            </form>
            <!-- Questions Type  -->

            <p>
                <a href="reset-password.php" class="btn btn-warning">Reset Password</a>
                <a href="logout.php" class="btn btn-danger ml-3">Sign Out</a>
            </p>
        </div>
        <!-- Centre Block - END-->

        <!-- Right direction block - START-->
        <div class = "right-child">
            <img src="../assets/right_arrow.png" class = "right" id = "right"/>
        </div>
        <!-- Right direction block - END -->
    </div>


    <!-- Modal popup box - START -->
    <div id="mpopupBox" class="mpopup">
        <div class="modal-content">
            <div class="modal-header">
                <h2>NDA content</h2>
                <span class="close">×</span>
            </div>
            <div class="modal-body">
                <p>Insert content here...</p>
                <p>Insert content here...</p>
                <p>Insert content here...</p>
                <p>Insert content here...</p>
                <p>Insert content here...</p>
            </div>
            <div class="modal-footer">
                <button type="button" id = "goBackBtn" class="btn btn-primary">Go back</button>
            </div>
        </div>
    </div>
    <!-- Modal popup box - END -->
        
</body>

<script>

    let RequirementContent = document.getElementById('RequirementContent');
    let RequirementDigital = document.getElementById('RequirementDigital');
    let RequirementOnGround = document.getElementById('RequirementOnGround');
    let RequirementHybrid = document.getElementById('RequirementHybrid');
    
    if(RequirementContent.checked || RequirementDigital.checked || RequirementOnGround.checked || RequirementHybrid.checked){
        let javaScriptVar = <?php echo $jsonResponse; ?>;
        
        //  page counter 
        let counter = 0
        //  total page variable 
        let totalPages = 0

        const questionId = [];
        // hide requirement radio buttons 
        document.getElementById("frm1").style.display  = "none";


        for(var i=0; i<javaScriptVar.res.length; i++){
            if(javaScriptVar.res[i].ResponseType === "text"){
                document.getElementById('questionsForm').innerHTML += `
                    <div  class="form-group" id = "question`+(javaScriptVar.res[i].QuestionId)+`">
                        <p>`+(i+1)+`) `+javaScriptVar.res[i].Question+`<span style="color:red;">*</span></p>
                        <input type = "text" name = "response`+(i)+`"  id= "response`+(i)+`" required/>
                    </div>
                `;
                totalPages +=1; 
            }else if(javaScriptVar.res[i].ResponseType === "dropdown"){
                //
                document.getElementById('questionsForm').innerHTML += `
                    <div  class="form-group" id = "question`+(javaScriptVar.res[i].QuestionId)+`">
                        <p for="'dropdown`+(i)+`">`+(i+1)+`) `+javaScriptVar.res[i].Question+`<span style="color:red;">*</span></p>
                        <select name = "response`+(i)+`" id= "response`+(i)+`" class="custom-select" style="width:150px;" form = "questionsForm" required>
                            <option selected disabled value="">Choose one..</option>
                        </select>
                    </div>
                `;
                totalPages +=1;
                // console.log(javaScriptVar.res[i].Options[0]);
                // For dropdown options - START 
                let dropdownoptionsArray = javaScriptVar.res[i].Options[0];
                
                for(j=0;j<dropdownoptionsArray.length;j++){
                    // console.log(optionsArray[j]);
                    document.getElementById('response'+(i)).innerHTML += `
                        <option value = "`+dropdownoptionsArray[j].OptionText+`">`+dropdownoptionsArray[j].OptionText+`</option>
                    `;        
                }
                // For dropdown options - END
            }else if(javaScriptVar.res[i].ResponseType === "radiobutton"){
                // console.log( javaScriptVar.res[i].Options[0]);
                let radiobtnoptionsArray = javaScriptVar.res[i].Options[0];
                document.getElementById('questionsForm').innerHTML += `
                    <div  class="form-group" id = "question`+(javaScriptVar.res[i].QuestionId)+`">
                        <p>`+(i+1)+`) `+javaScriptVar.res[i].Question+`<span style="color:red;">*</span></p>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons" id = "radiotext`+(i)+`">
                        </div>  
                    </div>  
                    `;  

                    totalPages +=1;
                for(j=0;j<radiobtnoptionsArray.length;j++){
                    // console.log(optionsArray[j]);
                    document.getElementById('radiotext'+(i)).innerHTML += `
                        <label class="btn radiobtn" >
                            <input type="radio" name="radiotext" value="`+radiobtnoptionsArray[j].OptionText+`" id="radiobtn`+radiobtnoptionsArray[j].OptionId+`">
                            `+radiobtnoptionsArray[j].OptionText+`
                        </label>
                    `;        
                }

            }else if(javaScriptVar.res[i].ResponseType === "file"){
                document.getElementById('questionsForm').innerHTML += `
                    <div  class="form-group" id = "question`+(javaScriptVar.res[i].QuestionId)+`">
                        <p>`+(i+1)+`) `+javaScriptVar.res[i].Question+`</p>
                        <input type = "file" name = "response`+(i)+`" id= "file" />
                    </div>
                `;
                totalPages +=1;
            }
            
            questionId.push(javaScriptVar.res[i].QuestionId);
        }

        document.getElementById('questionsForm').innerHTML +=`
        <div Id="questionSubmit">
            <div class="form-group">
                <input type = "checkbox"  name="terms" value="1" required> 
                I Agree  
                <a href="javascript:void(0);" class = "NDAText" id="mpopupLink">
                    NDA
                </a>
                <span class="invalid-feedback"></span>
            </div>  
            <input type="submit" class="btn btn-primary" name = "responseSubmit" id = "responseSubmit" value="Submit">
        </div>
        `;
        
        showFirstReq(javaScriptVar, 1);
        
        //Set counter as 1 for the default page one
        counter = 0;
        console.log(totalPages)
        // Navigating pages block - start
        document.getElementById('left').addEventListener('click', function(e) {
            console.log('counter left : '+counter);
            
            if(counter<=javaScriptVar.res.length && counter>0){
                document.getElementById('left').style.display = "block";
                document.getElementById('right').style.display = "block";
                counter-=1;
                if(counter<0){
                    showFirstReq(jsArr);
                    document.getElementById('left').style.display = "none";
                }
                navigatePage(questionId, counter);
            }else if(counter === 0){
                showFirstReq(javaScriptVar, 0);
                document.getElementById("frm1").style.display  = "block";
                document.getElementById('left').style.display = "none";
            }else if(counter<0){
                console.log("Can't move left");
            }
            console.log(counter);
        });
        
        document.getElementById('right').addEventListener('click', function(e) {
            console.log('counter right : '+counter);
            if(counter<javaScriptVar.res.length){
                document.getElementById("frm1").style.display  = "none";
                document.getElementById('left').style.display = "block";
                document.getElementById('right').style.display = "block";
                counter+=1;
                if(counter>=javaScriptVar.res.length){
                   
                    document.getElementById('right').style.display = "none";
                    document.getElementById('questionSubmit').style.display = "block";
                }
                navigatePage(questionId, counter);
            }else if(counter === 0){
                console.log("IN");
                showFirstReq(javaScriptVar, 1);
            }else{
                console.log("Can't move right");
            }
            // console.log(counter);
        });
        // Navigating pages block - end



        // For modal popup - START
        // Select modal
        var mpopup = document.getElementById('mpopupBox');

        // Select trigger link
        var mpLink = document.getElementById("mpopupLink");

        // Select close action element
        var close = document.getElementsByClassName("close")[0];


        // Select close action element
        var goBackBtn = document.getElementById("goBackBtn");

        // Open modal once the link is clicked
        mpLink.onclick = function() {
            mpopup.style.display = "block";
        };

        // Close modal once close element is clicked
        close.onclick = function() {
            mpopup.style.display = "none";
        };

        // Close modal once goBackBtn button is clicked
        goBackBtn.onclick = function() {
            mpopup.style.display = "none";
        };

        // Close modal when user clicks outside of the modal box
        window.onclick = function(event) {
            if (event.target == mpopup) {
                mpopup.style.display = "none";
            }
        };
        // For modal popup - END
    }

</script>
</html>


