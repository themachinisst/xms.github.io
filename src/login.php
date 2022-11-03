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
$email = $password = "";
$email_err = $password_err = $login_err = "";

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
        $sql = "SELECT Id, Email, Password FROM login WHERE Email = ?";

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
                    $stmt->bind_result($id, $email, $hashed_password);

                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            //Password is correct, so start a new session
                            session_start();

                            //store data in session variables

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["Email"] = $email;
                            //$_SESSION["name"] = $name;

                            //Redirect user to welcome page
                            header("location: welcome.php");
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
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Let's Make it happen <br/>Together</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="Email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="Password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>