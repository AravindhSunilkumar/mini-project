<?php
session_start();

include('connection.php');

// Function for user signup
function userSignUp($username, $email, $password, $confpassword)
{
    global $conn;
    $sql = "SELECT user_username FROM tbl_users WHERE user_username = '$username'";
    $sql2 = "SELECT * FROM tbl_users WHERE user_email = '$email'";
    $result = $conn->query($sql);
    $result2 = $conn->query($sql2);

    if (($result->num_rows > 0) || ($result2->num_rows>0)) {
        // Name exists in the table
       // echo '<script>alert("Patient With Same email is already exist");</script>';
        return array("success" => false, "message" => "Patient With Same email is already exist");
    } else {
        // Name doesn't exist in the table
        //echo '<script>alert("Name not found in the database.");</script>';


        if ($password == $confpassword) {

            $sql = "INSERT INTO tbl_users (user_username,user_email, user_password) VALUES ('$username', '$email', '$password')";

            if (mysqli_query($conn, $sql)) {
                $_SESSION["name"] = $username;
                $_SESSION["email"] = $email;
                $_SESSION['password'] = $password; 

                return array("success" => true, "message" => "User registered successfully.");
            } else {
                return array("success" => false, "message" => "you already registered with this email address");
            }
        } else {
            return array("success" => false, "message" => "Passwords do not match.");
        }
    }
}

// Function for user login
function userLogin($useremail, $password)
{
    global $conn;

    $sql = "SELECT * FROM tbl_users WHERE user_email = '$useremail' AND user_password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        
        $_SESSION['name'] = $row['user_username'];  
        $_SESSION['user']='user';

        $_SESSION['email'] = $row['user_email'];  
        $_SESSION['password'] = $row['user_password'];  
        return array("success" => true, "redirect" => "index.html");
    } else {
        $sql = "SELECT * FROM tbl_admin WHERE email = '$useremail' AND admin_password = '$password'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) > 0) {
        $_SESSION['user']='admin';
        $_SESSION['name'] = $row['admin_username'];
            return array("success" => true, "redirect" => "admin_menu.php");
        } else {
            $sql = "SELECT * FROM tbl_doctors WHERE email = '$useremail' AND password = '$password'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
    
            if (mysqli_num_rows($result) > 0) {
                $_SESSION['user']='d';
                $_SESSION['name'] = $row['doctor_name'];
                return array("success" => true, "redirect" => "index.html");
            } else {
                return array("success" => false, "message" => "Invalid email or password");
            }
           
        }
    }
}

// Check if the request is for signup or login
if (isset($_POST["signup"])) {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confpassword = $_POST['confpassword'];

    $result = userSignUp($username, $email, $password, $confpassword);
        


    if ($result["success"]) {
        if (isset($result["redirect"])) {
            header('Location: ' . $result["redirect"]);
        } else {
            header('Location: index.html');
        }
    } else {
        $_SESSION['message2'] = $result["message"];
        header('Location: signup.php');
    }
    exit();
}
if (isset($_POST["login"])) {
    $useremail = $_POST['email'];
    $password = $_POST['password'];
    $result = userLogin($useremail, $password);

    if ($result["success"]) {
        if (isset($result["redirect"])) {
            header('Location: ' . $result["redirect"]);
        } else {
            header('Location: index.html');
        }
    } else {
        $_SESSION["message1"] = $result["message"];
        header('Location: signup.php');
    }
    exit();
}
