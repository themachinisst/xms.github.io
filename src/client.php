<?php

//Initialize the session
session_start();
include_once('../src/api/jwt.php');

//Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
$Organization = $OrganizationName = $SubAgency = $BrandName = $Id = "";



if (isset($_POST['Organization'])) {
    $Id = $_SESSION["id"];
    $Organization = $_POST['Organization'];
    // print_r($Organization);
    if ($Organization == 'Client') {
        // echo "in";
        $OrganizationName = $_POST['ClientOrganizationName'];
        $SubAgency = 'NULL';
        $BrandName = $_POST['ClientBrandName'];
    } else if ($Organization == 'Agency') {
        // echo "out";
        $OrganizationName = $_POST['AgencyOrganizationName'];
        $SubAgency = $_POST['SubAgency'];
        $BrandName = $_POST['AgencyBrandName'];
    }
    //if($responseRes === 'success'){
    $urltype = 'http://localhost/xms/src/api/clientType.php';

    $postDatatype = array("Id" => $Id, "Organization" => $Organization, "OrganizationName" => $OrganizationName, "SubAgency" => $SubAgency, "BrandName" => $BrandName);
    // print_r($postDatatype);
    $jsonResponsetype = rest_call('POST', $urltype, $postDatatype, $responsetype, 'multipart/form-data', "Bearer " . $_COOKIE['kpmg-access']);
    $responsetype = json_decode($jsonResponsetype, true);
    header("location: welcome.php");
    //echo $Id;

    // print_r($jsonResponsetype);
    // echo'done';
} else {
    // echo 'waiting for response';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/577845f6a5.js" crossorigin="anonymous">
    </script>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./js/client.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../style/client_style.css">
    <link rel="stylesheet" href="../style/main.css">
    <style>
        body {
            font: 14px sans-serif;
            text-align: center;
        }
    </style>

</head>

<body>
    <div class="parent">
        <!-- Left direction block - START-->
        <div class="left-child">
            <a href="client.php"><img src="../assets/left_arrow.png"></a>

        </div>
        <!-- Centre Block - START-->
        <div class="child">

            <!-- Requirement Type  -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="form2" id="form2">

                <!-- ratio button and top text -->
                <div class="form-group" id='hide'>
                    <br />
                    <br />
                    <p class="Text">Hey! who are you ?</p>
                    <br />
                    <div class="btn-group-toggle" id="radioid" data-toggle="buttons">
                        <label class="btn radiobtn">
                            I’m a Client
                            <input type="radio" name="Organization" value="Client" onclick="myFunctions()" />
                        </label>
                        <label class="btn radiobtn">
                            I’m a Agency
                            <input type="radio" name="Organization" value="Agency" onclick="myFunctions()" />
                        </label>
                    </div>

                </div>

                <!-- welcome Questions for Agency -->
                <div class="Agency" id="Agency">
                    <br />
                    <label for="AgencyOrganizationName">Super ! So whats your cool Agency called ?</label>
                    <input type="text" class="form-control form-control-lg" id="id1" name="AgencyOrganizationName" placeholder="Enter your Agency Name"><br>
                    <label for="SubAgency">And the Sub- Agency is ?</label>
                    <input type="text" class="form-control form-control-lg" id="id2" name="SubAgency" placeholder="Type here"><br>
                    <label for="AgencyBrandName">And the Brand / Client is ?</label>
                    <input type="text" class="form-control form-control-lg" id="id3" name="AgencyBrandName" placeholder="Type here">
                </div>
                <!-- welcome Questions for Client -->
                <div class="Client" id="Client"><br><br>
                    <label for="ClientOrganizationName">Nice, so whats your company name ?</label>
                    <input type="text" class="form-control form-control-lg" id="id4" name="ClientOrganizationName" placeholder="Enter your Company Name"><br>
                    <label for="ClientBrandName">And which brand do you want to talk about ?</label>
                    <input type="text" class="form-control form-control-lg" id="id5" name="ClientBrandName" placeholder="Type here">
                </div>
            </form>
            <!-- Centre Block - END-->
        </div>
        <!-- Left direction block - START-->
        <div class="right-child">

            <img src="../assets/right_arrow.png" onclick=requirement1() />
        </div>

        <!-- Left direction block - END-->
    </div>
</body>
<!-- script for selected radio button content(questions) - START-->
<script type="text/javascript">
    $(document).ready(function() {
        $(".Client,.Agency").hide()
        $('input[type="radio"]').click(function() {
            var inputValue = $(this).attr("value");
            var targetBox = $("." + inputValue);
            $(".Client,.Agency").not(targetBox).hide();
            $(targetBox).show();
        });
    });
</script>
<!-- script for selected radio button content(questions) - END-->

<script>
    $(document).ready(function() {
        $(".left-child,.right-child").hide()
        $('input[type="radio"]').click(function() {

            $(".left-child,.right-child").show();

        });
    });
</script>


<!-- script for hide content after selected radio button  - START-->
<script>
    function myFunctions() {
        var x = document.getElementById('hide');
        if (x.style.display === 'none') {
            hide.display = 'none';
        } else {
            x.style.display = 'none';
            hide.display = 'block';
        }
    }
</script>
<!-- script for hide content while selected radio button  - END-->
<script type="text/javascript">
    function requirement1() {
        let OrganizationName, SubAgency, BrandName = "";
        // var x = document.getElementById('radioid');

        let x = document.querySelector('input[name = Organization]:checked').value;
        console.log(x);
        // x.value;
        if (x === 'Agency') {

            OrganizationName = document.getElementById('id1');
            SubAgency = document.getElementById('id2');
            BrandName = document.getElementById('id3');

            console.log("in");
        } else if (x === 'Client') {
            // echo "out";
            OrganizationName = document.getElementById('id4');
            SubAgency = document.getElementById('id2');
            BrandName = document.getElementById('id5');
            console.log(OrganizationName.value);
            console.log(BrandName.value);
        }
        // else if( $SubAgency && $BrandName == "") {
        //     alert = 'please enter values';

        if (OrganizationName.value == "" || BrandName.value == "") {
            // show error prompt
            alert("Please input a Value");
            console.log(x);
            console.log(BrandName.value.length);

        } else {

            autoSubmit(form2);
            
            //    reture=true;

        }
       
    }
</script>

</script>

</html>