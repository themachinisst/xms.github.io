<?php
// To include config file, similar to "require" but
// only checks once if the file is included or not
require_once "config.php";

//defining all variabes below
$password = $confirm_password = "";
$password_err = $confirm_password_err = "";


//additional varibales 
$name = $lastname = $organization = $city = $subagency =  $email = $phone = $terms = $radio = $salescode = "";
$name_err = $LastName_err = $organization_err = $city_err = $subagency_err =  $email_err =  $phone_err = $terms_err = $radio_err  = $salescode_err ="";



//Processing form data when form is submitted 

if($_SERVER["REQUEST_METHOD"] == "POST"){

    //to validate passsword
    if(empty(trim($_POST["Password"]))){
        $password_err = "Please enter a password.";
    }elseif(strlen(trim($_POST["Password"])) < 8 ){
        $password_err = "Password must have at least 8 characters.";
    }else{
        $password = trim($_POST["Password"]);
    }

    //Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    }else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match";
        }
    }

    

    //For user's name ----------START-------------------
    
    //validate name
    if(empty(trim($_POST["Name"]))){
        $name_err = "Please enter a valid name.";
    }else{
        //Prepare a select statement
        $sql = "SELECT Id from login WHERE Name = ?";

        // $mysqli->prepare function is used to prepare an SQL statement for execution.
        if($stmt = $mysqli->prepare($sql)){
            //bind variabes to the prepared statement as parameters
            $stmt->bind_param("s",  $param_name);

            //set parameters
            $param_name = trim($_POST["Name"]);

            //attempt to execute the prepared statement 
            if($stmt->execute()){
                
                //store results
                $stmt->store_result();
                $name = trim($_POST["Name"]);
            }else{
                echo "OOPs! Something went wrong. Please try again !";
            }
            //close statement
            $stmt->close();
        }
    }

    //For user's name -----------END--------------------

    //For radio button to be selected ----------START-------------------
    // if(!(isset($_POST["ClientType"]))){
    //     $radio_err = "Please select one of the options.";
    // }else{
    //     $radio = $_POST["ClientType"];
    // }

    //For radio button to be selected -----------END--------------------

    //For user's organization ----------START-------------------
        
    // if((empty($_POST["Organization"])) && $radio == 'Client'){
    // if(empty(trim($_POST["Organization"])) && isset($_POST["ClientType"]) && $radio == "Client"){
    //     $organization_err = "Please enter a valid organization name.";
    //     // $organization_err = $radio ;
    // }else{
    //     //Prepare a select statement
    //     $sql = "SELECT Id from login WHERE Organization = ?";

    //     // $mysqli->prepare function is used to prepare an SQL statement for execution.
    //     if($stmt = $mysqli->prepare($sql)){
    //         //bind variabes to the prepared statement as parameters
    //         $stmt->bind_param("s",  $param_organization);

    //         //set parameters
    //         $param_organization = trim($_POST["Organization"]);

    //         //attempt to execute the prepared statement 
    //         if($stmt->execute()){
                
    //             //store results
    //             $stmt->store_result();
    //             $organization = trim($_POST["Organization"]);
    //         }else{
    //             echo "OOPs! Something went wrong. Please try again !";
    //         }
    //         //close statement
    //         $stmt->close();
    //     }
    // }

    //For user's organization -----------END--------------------

    //For user's subagency ----------START-------------------
        
    // if((trim($_POST["SubAgency"]))){
    //     //Prepare a select statement
    //     $sql = "SELECT Id from login WHERE SubAgency = ?";

    //     // $mysqli->prepare function is used to prepare an SQL statement for execution.
    //     if($stmt = $mysqli->prepare($sql)){
    //         //bind variabes to the prepared statement as parameters
    //         $stmt->bind_param("s",  $param_subagency);

    //         //set parameters
    //         $param_subagency = trim($_POST["SubAgency"]);

    //         //attempt to execute the prepared statement 
    //         if($stmt->execute()){
                
    //             //store results
    //             $stmt->store_result();
    //             $subagency = trim($_POST["SubAgency"]);
    //         }else{
    //             echo "OOPs! Something went wrong. Please try again !";
    //         }
    //         //close statement
    //         $stmt->close();
    //     }
    // }
    //For user's subagency -----------END--------------------

    //For user's LastName -----------START--------------------
    if(empty(trim($_POST["LastName"]))){
        $lastname_err = "Please enter a valid Last Name.";
    }else{
        //Prepare a select statement
        $sql = "SELECT Id from login WHERE LastName = ?";

        // $mysqli->prepare function is used to prepare an SQL statement for execution.
        if($stmt = $mysqli->prepare($sql)){
            //bind variabes to the prepared statement as parameters
            $stmt->bind_param("s",  $param_lastname);

            //set parameters
            $lastname = trim($_POST["LastName"]);

            //attempt to execute the prepared statement 
            if($stmt->execute()){
                
                //store results
                $stmt->store_result();
                $lastname = trim($_POST["LastName"]);
            }else{
                echo "OOPs! Something went wrong. Please try again !";
            }
            //close statement
            $stmt->close();
        }
    }
//For user's LastName -----------END--------------------
    

    //For Sales Code  ----------START-------------------
    if(empty(trim($_POST["SalesCode"]))){
        $salescode_err = "Please enter a valid Sales Code, provided by our sales team.";
    }else if(!in_array($_POST["SalesCode"], $SalesCodeValid)){
        $salescode_err = "Incorrect Sales Code.";
    }else{
        //Prepare a select statement
        $sql = "SELECT Id from login WHERE SalesCode = ?";

        // $mysqli->prepare function is used to prepare an SQL statement for execution.
        if($stmt = $mysqli->prepare($sql)){
            //bind variabes to the prepared statement as parameters
            $stmt->bind_param("s",  $param_salescode);

            //set parameters
            $param_salescode = trim($_POST["SalesCode"]);

            //attempt to execute the prepared statement 
            if($stmt->execute()){
                
                //store results
                $stmt->store_result();
                $salescode = trim($_POST["SalesCode"]);
            }else{
                echo "OOPs! Something went wrong. Please try again !";
            }
            //close statement
            $stmt->close();
        }
    }
    //For Sales Code  -----------END--------------------



    //For user's email ----------START-------------------
    
    //validate name
    if(empty(trim($_POST["Email"]))){
        $email_err = "Please enter a valid email.";
    }elseif(!preg_match('/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+.[a-zA-Z]$/', trim($_POST["Email"]))){
        $email_err = "Please enter a valid email ID.";
    }else{
        //Prepare a select statement
        $sql = "SELECT Id from login WHERE Email = ?";

        // $mysqli->prepare function is used to prepare an SQL statement for execution.
        if($stmt = $mysqli->prepare($sql)){
            //bind variabes to the prepared statement as parameters
            $stmt->bind_param("s",  $param_email);

            //set parameters
            $param_email = trim($_POST["Email"]);

            //attempt to execute the prepared statement 
            if($stmt->execute()){
                
                //store results
                $stmt->store_result();
                $email = trim($_POST["Email"]);
            }else{
                echo "OOPs! Something went wrong. Please try again !";
            }
            //close statement
            $stmt->close();
        }
    }

    //For user's email -----------END--------------------

    //For user's age ----------START-------------------
    

    //For user's age -----------END--------------------

    //For user's phone ----------START-------------------
    
    //validate name
    if(empty(trim($_POST["Phone"]))){
        $phone_err = "Please enter a valid phone number.";
    }else if(strlen((string)trim($_POST["Phone"])) > 10 || strlen((string)trim($_POST["Phone"])) < 10){
        $phone_err = "Please enter a valid phone number.";
    }else{
        //Prepare a select statement
        $sql = "SELECT Id from login WHERE Phone = ?";

        // $mysqli->prepare function is used to prepare an SQL statement for execution.
        if($stmt = $mysqli->prepare($sql)){
            //bind variabes to the prepared statement as parameters
            $stmt->bind_param("i",  $param_phone);

            //set parameters
            $param_phone = trim($_POST["Phone"]);

            //attempt to execute the prepared statement 
            if($stmt->execute()){
                
                //store results
                $stmt->store_result();
                $phone = trim($_POST["Phone"]);
            }else{
                echo "OOPs! Something went wrong. Please try again !";
            }
            //close statement
            $stmt->close();
        }
    }

    //For user's phone -----------END--------------------

    //For checking terms and condition's checkbox  ----------START-------------------
    if($_POST["terms"] != 1){
        $terms_err = "Please go through our Terms and Conditions.";
    }
    //For checking terms and condition's checkbox  ----------END-------------------


    //Check input errors before inserting in data base  salescode_err
    if(empty($password_err) && empty($confirm_password_err) && empty($name_err) && empty($lastname_err) && empty($radio_err) && empty($salescode_err) && empty($organization_err) && empty($email_err) && empty($phone_err)){
            
        // Prepare an insert statement
        $sql = "INSERT INTO login (Password, Name, LastName, Organization, SubAgency, Email, SalesCode, Phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            //Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssssi", $param_password, $param_name, $param_lastname, $param_organization, $param_subagency, $param_email, $param_salescode, $param_phone);

            //set parameters 
            $param_name = $name;
            $param_lastname = $lastname;
            $param_organization = $organization;
            $param_subagency = $subagency;
            $param_email = $email;
            $param_salescode = $salescode;
            $param_phone = $phone;
            


            //creates a password hash
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            //attempt to execute the prepared statement
            if($stmt->execute()){
                //Redirect to login page
                header("location: login.php");
            }else{
                echo "OOPs! Something went wrong. Please try again later !".mysqli_error($mysqli).$phone;
            }

            //close statement
            $stmt->close();
        }
    }

    //close connection 
    $mysqli->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/first2style.css">
    <link rel="stylesheet" href="../style/register_style.css">
    <script type="text/javascript" src="./js/register.js"></script>
    <!-- <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }

        .wrapper{
            
            border-radius: 4px;
        }
    </style> -->
</head>
<script>
    function showState(state)
    {
        document.frm.submit();
    }

    function showCity(city)
    {
        document.frm.submit();
    }

    function validateText(elem) {
        // var element = document.getElementById(elem.id);
        if(elem.name === 'Name'){
            elem.value = elem.value.replace(/[^a-zA-Z]+/, '');
        }else if(elem.name === 'Phone'){
            elem.value = elem.value.replace(/[^0-9]+/, '');
        }
    };
</script>
<body>
<div class = "main-div">
    <div class="parent-div">
    <div>
                <center>
                    <h1>Let's Make it happen Together</h1>
                    <h5>Lorem Ipsum is simply dummy text of the printing and typesetting 
                        industry. Lorem Ipsum has been the industry</h5>
                </center>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"  name="frm" id="frm" class = "frm">
            <div class="form">
                
                <input type="text" name="Name" onkeyup="validateText(this);" class="ip1 <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" placeholder="First Name" required>
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                <input type="text" name="LastName" onkeyup="validateText(this);" class="ip2 <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>" placeholder="Last Name" required>
                <span class="invalid-feedback"><?php echo $LastName_err; ?></span>
                <input type="tel" maxlength="10" name="Phone" onkeyup="validateText(this);" class="ip3" <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>" placeholder="Mobile Number" required>
                <span class="invalid-feedback"><?php echo $phone_err; ?></span>
                <input type="email" name="Email" class="ip4 <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="Email Address" required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                
            <!-- Client Type  -->
            <!-- <div class="form-group">
                <br/>
                <ul>
                    <li class = "ClientTypes">
                        <input type="radio" name="ClientType"  <?php if ($radio == 'Content') { ?>checked='checked' <?php } ?>  class="<?php echo (!empty($radio_err)) ? 'is-invalid' : ''; ?>" value="Client" id="ClientTypesClient" onclick="displayClientInfo(this)"/>
                        <label class = "ClientTypesRadioBtns" >Client</label>
                        <input type="radio" name="ClientType"  <?php if ($radio == 'Content') { ?>checked='checked' <?php } ?>  class="<?php echo (!empty($radio_err)) ? 'is-invalid' : ''; ?>"  value="Agency" id="ClientTypesAgency" onclick="displayClientInfo(this)"/>
                        <label class = "ClientTypesRadioBtns" >Agency</label>
                    <span class="invalid-feedback"><?php echo $radio_err; ?></span>
                    </li>
                </ul>
            </div> -->
            
            <!-- Client Type  -->

            <!-- <div class="form-group client">
                <label>Organization Name <span style="color:red;">*</span></label>
                    <input type="text" name="Organization" class="form-control <?php echo (!empty($organization_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $organization; ?>">
                    </input>
                <span class="invalid-feedback"><?php echo $organization_err; ?></span>
            </div>    

            <div class="form-group agency">
                <label>Sub Agency Name </label>
                    <input type="text" name="SubAgency" class="form-control <?php echo (!empty($subagency_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $subagency; ?>">
                    </input>
                <span class="invalid-feedback"><?php echo $subagency_err; ?></span>
            </div>    
            -->
    
            
                <input type="password" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false"  name="Password" class="ip5 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" placeholder="Password" required>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
           
                <input type="password" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" name="confirm_password" class="ip6 <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" placeholder="Re-enter Password" required>
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>

                <input type="text" pattern="[a-zA-Z0-9]+" name="SalesCode" class="ip7 <?php echo (!empty($salescode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salescode; ?>" placeholder="Sales Code" required>
                <span class="invalid-feedback"><?php echo $salescode_err; ?></span>
            
            </div>

            <div class="chkbx">
                <input type = "checkbox"  name="terms" value="1" class="<?php echo (!empty($terms_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $$terms; ?>"required> 
                I Agree to 
                <a href="javascript:void(0);" class = "TermsConditionsText" id="mpopupLink">
                        Terms And Conditions
                </a>
                <span class="invalid-feedback"><?php echo $terms_err; ?></span>
            </div>

            <div class="butn">
                <input type="submit" class="btn btn-secondary ml-2" value="Submit">
                <!-- <input type="reset" class="btn btn-secondary ml-2" value="Reset"> -->
                <!-- <div class="nav-bar"></div> -->
            </div>
            <div class="footer">
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </div>
            
        </form>
        
    </div>    

</div>

<!-- Modal popup box - START -->
<div id="mpopupBox" class="mpopup">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Terms and Conditions content</h2>
            <span class="close">×</span>
        </div>
        <div class="modal-body">
           <p>Insert content here...</p>
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
</script>

</html>