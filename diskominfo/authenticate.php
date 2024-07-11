<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM account_user WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        if($_SESSION['role']==1){
            header("Location: welcome.php");
        }else{
            header("Location: welcome_user.php");  
        }
    } else {
        header("Location: login.php?error=1");
    }
}
?>
