<?php
session_start();

include('connection.php');

if (isset($_POST["signup"])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confpassword = $_POST['confpassword'];

    if ($password == $confpassword) {
        $sql = "INSERT INTO tbl_visitors (name, email, password) VALUES ('$name', '$email', '$password')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION["name"] = $name;
            $_SESSION["email"] = $email;
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['message2'] = "Error: " . mysqli_error($conn);
        }
    } else {
        $_SESSION["message2"] = "Passwords do not match.";
        header('Location: signup.php');
        exit();
    }
}

if (isset($_POST["login"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM tbl_visitors WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        // User exists, perform further actions
        // For example, set session variables or redirect to a logged-in page
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name'];

        if ($row['name'] == "admin") {
            header("Location: admin_menu.php");
        } else {
            header('Location: index.htnl');
            exit();
        }
    } else { 
       $_SESSION["message1"] = "Invalid email or password";
       
        header('Location: signup.php');
        exit();
    }
}
?>
