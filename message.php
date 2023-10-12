<?php
function email(){
require 'vendor/autoload.php';
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    // Use SMT
    $mail->isSMTP();
    
    // SMTP settings
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = 'aravindhsunilkumar3@gmail.com';
    $mail->Password = 'yupv olta vzjz hroo';                 
    
    // Set 'from' email address and name
    $mail->setFrom('aravindhsunilkumar3@gmail.com', 'Your Name');
    
    // Add a recipient email address
    $mail->addAddress('aravindhsunilkumar3@gmail.com');
    
    // Email subject and body
    $mail->Subject = 'Test Email';
    $mail->Body = 'sv jewellery vaikom http://localhost/PROJECT%202023/MINIPROJECT/code/loginpage.php';
    
    // Send email
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }
}
    ?>