<?php
include "includes/header.php";

if(isset($_SESSION['admin'])){
    header("Location: dashboard.php");
}
if(isset($_SESSION['name'])&&isset($_SESSION['email'])){
    header("Location: profile.php");
}

$set_value = 0;
$error = '0';
if(isset($_REQUEST['email'])&&isset($_REQUEST['pass'])){
    $email = trim($_REQUEST['email']);
    $pass = trim($_REQUEST['pass']);
    if($email=="admin@gmail.com"&&$pass=="admin"){
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
    }
    $pass = md5(trim($_REQUEST['pass']));
    $sql = "SELECT *
               FROM users
               WHERE user_email='$email'
               AND user_pass='$pass'";
    $result = mysqli_query($con, $sql);
    $num_rows = mysqli_num_rows($result);
    if($num_rows>0){
        $row = mysqli_fetch_array($result);
        $_SESSION['id'] = $row['user_id'];
        $_SESSION['name'] = $row['user_name'];
        $_SESSION['email'] = $row['user_email'];
        header("Location: home.php");
    } else{
        $set_value = 1;
        $error = "Invalid Email or Password";
    }
    
}



?>
<div class="col-md-9 main">
	<!-- login-page -->
	<div class="login">
		<div class="login-grids">
			<div class="col-md-6 log">
					 <h3 class="tittle" id = "signin">Sign In <i class="glyphicon glyphicon-lock"></i></h3>
					 <p id="login_instructions" style="display: block">Welcome, please enter the following to continue.</p>
            <?php
            if($set_value==1){
                echo '<div id="error" style="display: block;">
                        <div class="alert alert-danger" role="alert"">
                        <i class="glyphicon glyphicon-lock"></i>
                        <strong>Error! : </strong> <span id="error_message">'.$error.'</span>
                        </div>
                        </div>';
            }
            ?>
                    <div id = "form-area" style="display: block">
                         <form id = "form" method="post" action="login.php">
                             <h5>Email:</h5>
                             <input type="email" name="email" value="" id="input-email" placeholder="Enter Your Email ID" required>
                             <h5>Password:</h5>
                             <input type="password" name="pass" value="" id="input-pass" placeholder="Enter Your Password" required>
                             <input type="submit" value="Login" id="input-submit" style="display: block">
                         </form>
                    </div><div id="forgot-password">
                    <a href="#" onclick="showForgotPassword()">Forgot Password?</a>
                    <div id="forgot-password-form" style="display: none;">
                        <form id="forgot-password-form" method="post" action="">
                            <?php
include "includes/dbconn.php"; // Assuming you have a file to handle database connection

// Reset password logic
if(isset($_POST['email'], $_POST['new_password'])){
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    
    // Check if the email exists in the database
    $email_check_query = "SELECT * FROM users WHERE user_email='$email'";
    $email_check_result = mysqli_query($con, $email_check_query);

    if(mysqli_num_rows($email_check_result) > 0){
        // Update user's password in the database
        $hashed_password = md5($new_password); // You should use a stronger hashing algorithm like bcrypt or Argon2
        $update_password_query = "UPDATE users SET user_pass='$hashed_password' WHERE user_email='$email'";
        
        if(mysqli_query($con, $update_password_query)){
            $success_message = "Password reset successfully.";
        } else {
            $error_message = "Failed to reset password. Please try again later.";
        }
    } else {
        $error_message = "Email address not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <h2>Password Reset</h2>
    <?php
    if(isset($error_message)){
        echo '<div style="color: red;">' . $error_message . '</div>';
    } elseif(isset($success_message)){
        echo '<div style="color: green;">' . $success_message . '</div>';
    }
    ?>
    <form method="post" action="">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

                        </form>
                    </div>
                </div>
                <script>
                    function showForgotPassword() {
                        var forgotPasswordForm = document.getElementById('forgot-password-form');
                        if (forgotPasswordForm.style.display === 'none') {
                            forgotPasswordForm.style.display = 'block';
                        } else {
                            forgotPasswordForm.style.display = 'none';
                        }
                    }
                </script>



                    <script>
                    function validateForm() {
                      var email = document.getElementById("input-email").value.trim();
                        var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
                        if (!emailRegex.test(email)) {
                        alert("Invalid username");
                        return false; // Prevent form submission
                    }

        // Continue with form submission
                    return true; }
</script>
			</div>
            <div class="col-md-6 login-right">
                <h3 class="tittle">Why you should have to register with us <i class="glyphicon glyphicon-file"></i></h3>
                <p>By creating an account with our site, you will be able to move through the recruitment process faster,
                    view and apply to various companies by yourself and many more.</p>
                <a href="registration.php" class="hvr-bounce-to-bottom button">Create An Account</a>
            </div>
            <div class="clearfix"></div>
		</div>
    </div>
</div>
<!-- //login-page -->
<div class="clearfix"> </div>
