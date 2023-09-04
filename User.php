<?php
session_start();
include('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if($_SESSION['name'] === 'admin'){ 
    header('location:admin_menu.php'); 
    }else { ?>
    <h1>User</h1>
    <?php
    }
    ?>
</body>
</html>