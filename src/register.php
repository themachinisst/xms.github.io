<?php
// To include config file, similar to "require" but
// only checks once if the file is included or not
require_once "config.php";

//defining all variabes below
$password = $confirm_password = "";
$password_err = $confirm_password_err = "";


//additional varibales 
$name = $organization = $city = $subagency =  $email = $phone = $terms ="";
$name_err = $organization_err = $city_err = $subagency_err =  $email_err =  $phone_err = $terms_err = "";



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

    //For user's organization ----------START-------------------
        
    if((trim($_POST["Organization"]))){
        $organization_err = "Please enter a valid organization name.";
    }else{
        //Prepare a select statement
        $sql = "SELECT Id from login WHERE Organization = ?";

        // $mysqli->prepare function is used to prepare an SQL statement for execution.
        if($stmt = $mysqli->prepare($sql)){
            //bind variabes to the prepared statement as parameters
            $stmt->bind_param("s",  $param_organization);

            //set parameters
            $param_organization = trim($_POST["Organization"]);

            //attempt to execute the prepared statement 
            if($stmt->execute()){
                
                //store results
                $stmt->store_result();
                $organization = trim($_POST["Organization"]);
            }else{
                echo "OOPs! Something went wrong. Please try again !";
            }
            //close statement
            $stmt->close();
        }
    }

    //For user's organization -----------END--------------------

    //For user's subagency ----------START-------------------
        
    if((trim($_POST["SubAgency"]))){
        //Prepare a select statement
        $sql = "SELECT Id from login WHERE SubAgency = ?";

        // $mysqli->prepare function is used to prepare an SQL statement for execution.
        if($stmt = $mysqli->prepare($sql)){
            //bind variabes to the prepared statement as parameters
            $stmt->bind_param("s",  $param_subagency);

            //set parameters
            $param_subagency = trim($_POST["SubAgency"]);

            //attempt to execute the prepared statement 
            if($stmt->execute()){
                
                //store results
                $stmt->store_result();
                $subagency = trim($_POST["SubAgency"]);
            }else{
                echo "OOPs! Something went wrong. Please try again !";
            }
            //close statement
            $stmt->close();
        }
    }

    //For user's subagency -----------END--------------------

    //For user's Gender ----------START-------------------

     
    //For user's Gender -----------END--------------------
    //For user's BloodGroup ----------START-------------------
     
    //For user's  BloodGroup  -----------END--------------------



    //For user's email ----------START-------------------
    
    //validate name
    if(empty(trim($_POST["Email"]))){
        $email_err = "Please enter a valid email.";
    }elseif(!preg_match('/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/', trim($_POST["Email"]))){
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


    //Check input errors before inserting in data base
    if(empty($password_err) && empty($confirm_password_err) && empty($name_err) && empty($organization_err) && empty($subagency_err) && empty($email_err) && empty($phone_err)){
            
        // Prepare an insert statement
        $sql = "INSERT INTO login (Password, Name, Organization, SubAgency, Email, Phone) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            //Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssi", $param_password, $param_name, $param_organization, $param_subagency, $param_email, $param_phone);

            //set parameters 
            $param_name = $name;
            $param_organization = $organization;
            $param_subagency = $subagency;
            $param_email = $email;
            $param_phone = $phone;
            


            //creates a password hash
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            //attempt to execute the prepared statement
            if($stmt->execute()){
                //Redirect to login page
                header("location: login.php");
            }else{
                echo "OOPs! Something went wrong. Please try again later !";
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
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/register_style.css">
    <script type="text/javascript" src="../js/register.js"></script>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }

        .wrapper{
            /* margin-top:3%;
            margin-bottom:3%;
            background-color: grey; */
            border-radius: 4px;
        }
    </style>
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
</script>
<body>
<div class="split left">
  <!-- <div class="centered">
    <img src="../assets/registerBG.png" alt="registerBG" class = "registerBG">
    <h2>Jane Flex</h2>
    <p>Some text.</p>
  </div> -->
  <!-- <img src="../assets/registerBG.png" class = "registerBG" alt="registerBG"> -->
</div>


<div class = "split right">
    <div class="wrapper">
        <h2>Let's Make it happen Together</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"  name="frm" id="frm" class = "frm">
            <div class="form-group">
                <label>Name <span style="color:red;">*</span></label>
                <input type="text" name="Name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" required>
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>  
        
            <div class="form-group">
                <label>Phone <span style="color:red;">*</span></label>
                <input type="tel" maxlength="10" name="Phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>" required>
                <span class="invalid-feedback"><?php echo $phone_err; ?></span>
            </div>  

            <div class="form-group">
                <label>Email ID <span style="color:red;">*</span></label>
                <input type="email" name="Email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>"required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>  

            <!-- Client Type  -->
            <div class="form-group">
                <br/>
                <ul>
                    <li class = "ClientTypes">
                        <input type="radio" name="ClientTypes"  value="Client" id="ClientTypesClient" onclick="displayClientInfo(this)"/>
                        <label class = "ClientTypesRadioBtns" >Client</label>
                        <input type="radio" name="ClientTypes"  value="Agency" id="ClientTypesAgency" onclick="displayClientInfo(this)"/>
                        <label class = "ClientTypesRadioBtns" >Agency</label>
                    </li>
                </ul>
            </div>
            <!-- Client Type  -->

            <div class="form-group client">
                <label>Organization Name <span style="color:red;">*</span></label>
                    <input type="text" name="Organization" class="form-control <?php echo (!empty($organization_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $organization; ?>">
                    </input>
                <span class="invalid-feedback"><?php echo $organization_err; ?></span>
            </div>    

            <div class="form-group agency">
                <label>Sub Agency Name <span style="color:red;">*</span></label>
                    <input type="text" name="SubAgency" class="form-control <?php echo (!empty($subagency_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $subagency; ?>">
                    </input>
                <span class="invalid-feedback"><?php echo $subagency_err; ?></span>
            </div>    
            
    
            <div class="form-group">
                <label>Password <span style="color:red;">*</span></label>
                <input type="password" name="Password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>"required>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password <span style="color:red;">*</span></label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>"required>
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group">
                <input type = "checkbox"  name="terms" value="1" class="<?php echo (!empty($terms_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $$terms; ?>"required> I Agree to 
                <span class="invalid-feedback"><?php echo $terms_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</div>
</body>
</html>