<?php

//initialize the session 
session_start();

//check if the user is already logged in,
// if yes then  redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

require_once "config.php";

//Define variables and initialize with empty values
$email = $password = $name = $salescode = "";
$email_err = $password_err = $login_err = $name_err = $salescode_err = "";

//Processing from data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //Check if email is empty
    if(empty(trim($_POST['Email']))){
        $email_err = "Please enter Email";
    }else{
        $email = trim($_POST["Email"]);
    }

    //Check if password is empty
    if(empty(trim($_POST["Password"]))){
        $password_err = "Please enter your password.";
    }else{
        $password = trim($_POST["Password"]);
    }

    //validate credentirals 
    if(empty($email_err) && empty($password_err)) {
        //Prepare a select statement
        $sql = "SELECT Id, Email, Name, Password, SalesCode FROM login WHERE Email = ?";

        if($stmt = $mysqli->prepare($sql)){
            //Bind variables to the prepared statment as parameters 
            $stmt->bind_param("s", $param_email);

            //set parameters
            $param_email = $email;

            //Attempt to executre the prepared statement
            if($stmt->execute()){
                //store result
                $stmt->store_result();
                //check if email exists, if yes then verify the password
                if($stmt->num_rows==1){
                    //Bind result variables 
                    $stmt->bind_result($id, $email, $name, $hashed_password, $salescode);

                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            //Password is correct, so start a new session
                            session_start();

                            //store data in session variables

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["name"] = $name;
                            $_SESSION["salescode"] = $salescode;
                            
                            //To create folder by the Id name
                            $folderPath = "uploads/".$_SESSION["id"];
                            if (!file_exists($folderPath)) {
                                mkdir($folderPath, 0777, true);
                            }

                            //Redirect user to welcome page
                            // header("location: welcome.php");
                            header("location: client.php");
                        }else{
                            //Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";
                        }
                    }
                }else{
                    //email doesn't exist, display a generic error message
                    $login_err = "Invalid email or password.";
                }
            }else{
                echo "OOPs! Something went wrong. Please try again later.";
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
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/first2style.css">
    <link rel="stylesheet" href="../style/login_style.css">
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
<body>
<div class = "main-div">
<div class="parent-div">
            <div>
                <center>
                    <h1>Let’s make it happen together</h1>
                    <h5>Lorem Ipsum is simply dummy text of the printing and typesetting
                         industry. Lorem Ipsum has been the industry.</h5>
                </center>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form">
                <!-- <label>Email</label> -->
                <input type="text" placeholder = "Email Address" name="Email" class="ip1 <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form">
                <!-- <label>Password</label> -->
                <input type="password" placeholder = "Password" name="Password" class="ip2 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="fgpssowrd">
            <a href="reset-password.php">Forget Password?</a>
            </div>
            <div class="butn">
                <input type="submit" class="btn btn-secondary ml-2" value="Login">
                <!-- <div class="nav-bar"></div> -->
            </div>
            <div class="footer">
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            </div>
            
            </div>
        </form>
    </div>
</div>    
</body>
</html>