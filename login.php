<?php
include "db.php";
session_start();

if(isset($_POST["email"]) && isset($_POST["password"])){
    $email = mysqli_real_escape_string($con,$_POST["email"]);
    $password = $_POST["password"];
    $sql = "SELECT * FROM user_info WHERE email = '$email' AND password = '$password'";
    $run_query = mysqli_query($con,$sql);
    $count = mysqli_num_rows($run_query);
    $row = mysqli_fetch_array($run_query);
    if ($row) {
        $_SESSION["uid"] = $row["user_id"];
        $_SESSION["name"] = $row["first_name"];
        // Redirect if login successful
        header("Location: index.php");
        exit();
    } else {
        // Handle invalid credentials here (display error message or other action)
        echo "<span style='color:red;'>Invalid email or password..!</span>";
        exit();
    }
}
?>
