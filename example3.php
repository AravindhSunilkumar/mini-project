<?php
include("connection.php");
include("message.php");
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];

    // Check if the email exists in the database (replace 'tbl_users' with your actual table name)
    $sql = "SELECT * FROM tbl_users WHERE user_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If email exists, send a password reset email
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $userPassword = $row['user_password'];
      $subject="Password Recovery";
      $message="Password : " . $userPassword;
        email($email, $subject, $message);

        // Display SweetAlert with success message
        echo "Password sented to your email";
    } else {
        // Display SweetAlert with error message
        echo "Email not found. Please check your email and try again.";
    }



    $stmt->close();
    $conn->close();
}
?>