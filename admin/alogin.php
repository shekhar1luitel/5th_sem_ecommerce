<?php
session_start(); // Start the session
include("../db.php");
$msg = '';

function get_safe_value($con, $str) {
    // Implement your logic here to sanitize or validate the input
    return mysqli_real_escape_string($con, $str);
}

if (isset($_POST['submit'])) {
    $email = get_safe_value($con, $_POST['email']);
    $password = get_safe_value($con, $_POST['password']);

    $sql = "SELECT * FROM admin_info WHERE admin_email='$email' AND admin_password='$password'";
    $res = mysqli_query($con, $sql);
    $count = mysqli_num_rows($res);

    if ($count > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($row['status'] == '0') {
            $msg = "Account Deactivated";
        } else {
            $_SESSION['ADMIN_LOGIN'] = 'yes';
            $_SESSION['ADMIN_ID'] = $row['admin_id'];
            $_SESSION['ADMIN_USERNAME'] = $row['admin_name'];
            $_SESSION['ADMIN_ROLE'] = $row['admin_role'];
            header('location:index.php'); // Redirect to the dashboard or any other page
            exit();
        }
    } else {
        $msg = "Invalid Email Or Password";
    }
}
?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>LOGIN PAGE</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="assets/css/normalize.css">
   <link rel="stylesheet" href="assets/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/css/font-awesome.min.css">
   <link rel="stylesheet" href="assets/css/themify-icons.css">
   <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
   <link rel="stylesheet" href="assets/css/flag-icon.min.css">
   <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
   <link rel="stylesheet" href="assets/css/style.css">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   <style>
      body {
         /* background-image: url('images/background.jpg'); */
         background-repeat: no-repeat;
         background-attachment: fixed;
         background-size: cover;
         font-family: 'Open Sans', sans-serif;
         color: #fff;
      }
      .login-content {
         margin-top: 150px;
      }
      .login-form {
         background-color: rgba(0,0,0,0.7);
         padding: 30px;
         border-radius: 8px;
         box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
         animation: fadeInDown 1s ease;
      }
      @keyframes fadeInDown {
         from {
            opacity: 0;
            transform: translateY(-20px);
         }
         to {
            opacity: 1;
            transform: translateY(0);
         }
      }
      .btn-success {
         /* ... (your existing button styles) ... */
         transition: background-color 0.3s, transform 0.2s;
         transform-origin: center;
      }
      .btn-success:hover {
         background-color: #218838;
         transform: scale(1.05);
      }
      body {
         background-color: #222;
      }
      .login-form {
         background-color: rgba(0,0,0,0.7);
         padding: 30px;
         border-radius: 8px;
         box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
         animation: slideIn 1s ease;
         max-width: 400px;
         margin: 0 auto;
      }
      @keyframes slideIn {
         from {
            opacity: 0;
            transform: translateY(-20px);
         }
         to {
            opacity: 1;
            transform: translateY(0);
         }
      }
      .form-group {
         margin-bottom: 20px;
      }
      input[type="text"],
      input[type="password"] {
         padding: 10px;
         width: 100%;
         border-radius: 5px;
         border: none;
         background-color: rgba(255, 255, 255, 0.1);
         color: #fff;
         transition: background-color 0.3s;
      }
      input[type="text"]:focus,
      input[type="password"]:focus {
         outline: none;
         background-color: rgba(255, 255, 255, 0.3);
      }
      .btn-success {
         padding: 10px 20px;
         border-radius: 5px;
         border: none;
         background-color: #28a745;
         color: #fff;
         cursor: pointer;
         transition: background-color 0.3s, transform 0.2s;
      }
      .btn-success:hover {
         background-color: #218838;
         transform: scale(1.05);
      }
      .field_error {
         color: #ff0000;
         margin-top: 10px;
         animation: shake 0.5s ease;
      }
      .login-form a {
        color: #999; 
    }
    .login-form a:hover {
        color: #ccc; 
    }
      @keyframes shake {
         0% { transform: translateX(0); }
         25% { transform: translateX(-5px); }
         50% { transform: translateX(5px); }
         75% { transform: translateX(-5px); }
         100% { transform: translateX(0); }
      }
      /* Login Credentials error  */
      .error-container {
         text-align: center;
      }
      .field_error {
         color: #ff0000;
         margin-top: 10px;
         animation: shake 0.5s ease;
         display: inline-block;
      }
   </style>
</head>
<body class="bg-dark">
   <div class="sufee-login d-flex align-content-center flex-wrap">
      <div class="container">
         <div class="login-content">
            <div class="login-form">
               <form method="post">
               <!-- For The Login Credential error message  -->
               <div class="error-container">
                     <div class="field_error"><?php echo $msg?></div> <!-- Display error message here -->
                  </div>
                  
                  <div style="text-align: center; margin-top: 10px;">
                  <span><strong>Admin Login</strong></span>
                            </div> 
                  <div class="form-group">
                     <label>Email</label>
                     <input type="text" name="email" class="form-control" placeholder="Enter your email" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                     <label>Password</label>
                     <input type="password" name="password" class="form-control" placeholder="Enter your password" autocomplete="off" required>
                  </div>
                  <div style="text-align: center; margin-top: 20px;">
                   <button type="submit" name="submit" class="btn btn-success btn-flat">SIGN IN</button>
                           </div>
                  </form>

                  <!-- <div style="text-align: center; margin-top: 10px;">
                     <span>New member? </span>
                       <a href="register.php">Register here</a>
                            </div>  -->
                     <div style="text-align: center; margin-top: 10px;">
                     <a href="forgot_password.php">Forgot Password?</a>
                        </div>
            </div>
         </div>
      </div>
   </div>
   <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
   <script src="assets/js/popper.min.js" type="text/javascript"></script>
   <script src="assets/js/plugins.js" type="text/javascript"></script>
   <script src="assets/js/main.js" type="text/javascript"></script>
</body>
</html>
