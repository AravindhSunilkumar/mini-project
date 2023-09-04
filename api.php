<?php
session_start();

include('connection.php');

// Function for user signup
function userSignUp($username, $email, $password, $confpassword)
{
    global $conn;
    $sql = "SELECT user_username FROM tbl_users WHERE user_username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Name exists in the table
        echo '<script>alert("Name found in the database.");</script>';
    } else {
        // Name doesn't exist in the table
        //echo '<script>alert("Name not found in the database.");</script>';


        if ($password == $confpassword) {

            $sql = "INSERT INTO tbl_users (user_username,user_email, user_password) VALUES ('$username', '$email', '$password')";

            if (mysqli_query($conn, $sql)) {
                $_SESSION["name"] = $username;
                $_SESSION["email"] = $email;
                return array("success" => true, "message" => "User registered successfully.");
            } else {
                return array("success" => false, "message" => "Error: " . mysqli_error($conn));
            }
        } else {
            return array("success" => false, "message" => "Passwords do not match.");
        }
    }
}

// Function for user login
function userLogin($username, $password)
{
    global $conn;

    $sql = "SELECT * FROM tbl_users WHERE user_username = '$username' AND user_password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['name'] = $row['user_username'];
        return array("success" => true, "redirect" => "index.php");
    } else {
        $sql = "SELECT * FROM tbl_admin WHERE admin_username = '$username' AND admin_password = '$password'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['name'] = $row['admin_username'];
            return array("success" => true, "redirect" => "admin_menu.php");
        } else {
            return array("success" => false, "message" => "Invalid email or password");
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
            header('Location: index.php');
        }
    } else {
        $_SESSION['message2'] = $result["message"];
        header('Location: signup.php');
    }
    exit();
}
if (isset($_POST["login"])) {
    $username = $_POST['name'];
    $password = $_POST['password'];
    $result = userLogin($username, $password);

    if ($result["success"]) {
        if (isset($result["redirect"])) {
            header('Location: ' . $result["redirect"]);
        } else {
            header('Location: index.php');
        }
    } else {
        $_SESSION["message1"] = $result["message"];
        header('Location: signup.php');
    }
    exit();
}
