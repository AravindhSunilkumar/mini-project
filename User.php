<?php
session_start();
include('connection.php');
global $pay_amount;
global $otp;
// Include the message.php file
include('message.php');
global $patient_id;
$userid = $_SESSION['id'];
$username = $_SESSION['name'];
$password = $_SESSION['password'];
function patientid($conn, $userid)
{
    $sqlfetch = "SELECT patient_id FROM tbl_patient WHERE user_id = '$userid'";
    $result4 = $conn->query($sqlfetch);
    global $data;

    if ($result4->num_rows > 0) {
        while ($row = $result4->fetch_assoc()) {
            $data = $row['patient_id'];
        }
    }
    return $data;
}
$patient_id = patientid($conn, $userid);
$vali = '2';
$otp = 0;

$sql = "SELECT * FROM tbl_users WHERE user_username = '$username' AND user_password = '$password'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (mysqli_num_rows($result) > 0) {
    $userid = $row['user_id'];
}
function fetchTableData($conn, $tableName, $userid)
{
    $sql = "SELECT * FROM $tableName WHERE user_id = '$userid'";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}
$patients = fetchTableData($conn, "tbl_patient", $userid);
if (isset($_POST['insert'])) {
    
    $name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $allergyinfo = empty($_POST['allergy_info']) ? NULL : $_POST['allergy_info'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $userId = $_SESSION['id'];
    echo $phone;





    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == UPLOAD_ERR_OK) {

        $targetDir = "img/patients/";
        $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
        // Generate a unique filename
        $newFilename = uniqid() . '.' . $fileType;
    
        // Check if a file already exists in the target directory
        if (file_exists($targetDir . $newFilename)) {
            // Delete the existing file
            unlink($targetDir . $newFilename);
        }
    
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetDir . $newFilename)) {
            // File was successfully uploaded
            // Now insert the file address into the table
            $fileAddress = $targetDir . $newFilename;
        } else {
            echo "<script>alert('Error moving file to the target directory.')</script>";
        }
    } else {
        // Handle the case when no file is uploaded or an error occurs during the upload
        //echo "Error uploading file.";
    }
    


    // Process phone number to remove non-numeric characters
    $phone = preg_replace('/\D/', '', $phone);
    
    // Insert data into the table
    $sql = "UPDATE tbl_patient
            SET full_name = ?,gender = ?,date_of_birth = ?,address = ?,profile_picture = ?,allergy_info = ?,emergency_contact_phone = ? WHERE user_id = ? ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $name, $gender, $dob, $address, $fileAddress, $allergyinfo, $phone, $userId);



    if ($stmt->execute()) {
        echo '<script>
                var confirmed = confirm("Patient details edited successfully. Click OK to continue.");
                if (confirmed) {
                    window.location.href = "User.php";
                }
                </script>';
    } else {
        echo "Error inserting data: " . $stmt->error;
    }



    if (isset($_POST['profile'])) {
        echo "<script>alert('Upload a profile picture.')</script>";
        if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == UPLOAD_ERR_OK) {
            $targetDir = "img/patients/";
            $targetFile = $targetDir . basename($_FILES["profile_image"]["name"]);
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Generate a unique filename
            $newFilename = uniqid() . '.' . $fileType;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetDir . $newFilename)) {
                // File was successfully uploaded

                // Sanitize and validate user input here

                $userId = $_SESSION['id'];
                $fileAddress = $targetDir . $newFilename;

                // Perform SQL update
                $sql = "UPDATE tbl_patient SET profile_picture = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $fileAddress, $userId);

                if ($stmt->execute()) {
                    echo '<script>
                        var confirmed = confirm("Patient profile edited successfully. Click OK to continue.");
                        if (confirmed) {
                            window.location.href = "User.php";
                        }
                    </script>';
                } else {
                    echo "Error updating data: " . $stmt->error;
                }
            } else {
                echo "<script>alert('Error moving file to target directory.')</script>";
            }
        } else {
            echo "<script>alert('Upload a profile picture.')</script>";
        }
    }
}

function fetchName($conn, $id, $t_id, $tableName)
{
    $sql = "SELECT * FROM $tableName WHERE $t_id = $id ";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

//appointment details
function getAppointmentDetails($conn, $patient_id)
{
    $sql = "SELECT * FROM tbl_appointments WHERE patient_id = $patient_id ORDER BY created_at DESC ";
    $result = $conn->query($sql);
    $appmts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appmts[] = $row;
        }
    }
    return $appmts;
}
function getAppointmentDetail($conn, $patient_id)
{   
    
    $service_id='5';
    $sql = "SELECT * FROM tbl_appointments WHERE patient_id = $patient_id AND service_id=$service_id ORDER BY created_at DESC LIMIT 1 ";
    $result = $conn->query($sql);
    $appmtes = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appmtes[] = $row;
        }
    }
    return $appmtes;
}
if (isset($_POST['pay'])) {
    $payamount = intval($_POST['price']);

    header('location: payment.php?payamount=' . $payamount);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Smile 32</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="icon" href="./img/tooth.png" type="image/png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="lib/twentytwenty/twentytwenty.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- website Stylesheet -->
    <link rel="stylesheet" href="css/add_doctors.css">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">
    <style>
        html,
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Roboto", sans-serif
        }
    </style>

    <style>
        /* CSS for the modal dialog */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        /*image style */
        /* Style for the label */
        .file-label {
            display: inline-block;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            width: 70%;
            height: auto;
            margin-left: 60px;
        }

        /* Style for the hidden file input */
        .file-input {
            display: none;
        }

        /* Style for the label when the file input is clicked */
        .file-input+.file-label {
            background-color: #3498db;
            color: #fff;
        }

        /* Style for the label on hover */
        .file-label:hover {
            background-color: #060606;
            color: #fff;
            width: 70%;

        }

        /* Styles for the modal dialog */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        .modal-content {
            background-color: #fff;
            margin: 20% auto;
            padding: 20px;
            width: 60%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            text-align: center;
        }
    </style>
    
</head>
<?php if ($_SESSION['name'] === 'admin') {
        header('location:admin_menu.php');
    } else {
        $id=$_SESSION['id'];
        $sql = "SELECT * FROM tbl_patient WHERE user_id = $id ";
        $result = $conn->query($sql);
        
    
        if ($result->num_rows > 0) {}else{
            echo '<script>
            var confirmed = confirm("Make the first Appointment setup profile. Click OK to continue.");
            if (confirmed) {
                window.location.href = "index.php#services";
            }
        </script>';
        }
        foreach ($patients as $index => $patient) {



            // Function to generate a random OTP
            function generateRandomOTP()
            {
                return rand(1000, 9999);
            }

            if (isset($_POST['reset_password'])) {
                // Get the submitted username and password
                $submittedemail = $_POST['email'];
                $submittedPassword = $_POST['password'];
                $_SESSION['submittedPassword'] = $_POST['password'];

                // Get the stored username and password from the session
                $storedemail = $_SESSION['name'];
                $storedPassword = $_SESSION['password'];

                // Check if the submitted username and password match the stored values
                if ($submittedemail !== $storedemail || $submittedPassword !== $storedPassword) {
                    // No changes, display confirmation box with OTP input field
                    $otp = generateRandomOTP(); // Generate a random OTP

                    // Store the generated OTP in a session variable
                    $_SESSION['generated_otp'] = $otp;

                    // Send the OTP via email
                    email($_SESSION['email'], 'OTP Verification for changing password / username ' . $otp . '', 'Your OTP is: ' . $otp);

                    $vali = '1';
                } else {
                    // Changes detected, you can perform actions or redirect to a different page
                    // For example, you can update the username and password here
                    // Redirect to a different page
                    // header("Location: update_password.php");
                    // exit();
                }
            }

            // Check the OTP form submission
            if (isset($_POST['submit_otp'])) {
                $enteredOTP = intval(trim($_POST['otpinput']));

                // Retrieve the stored generated OTP from the session
                $storedOTP = isset($_SESSION['generated_otp']) ? $_SESSION['generated_otp'] : 0;
                $submittedPassword = isset($_SESSION['submittedPassword']) ? $_SESSION['submittedPassword'] : 0;

                // Check the entered OTP against the stored generated OTP
                if ($enteredOTP == $storedOTP) {
                    // OTP verification succeeded
                    // Perform the SQL update only if OTP was verified
                    $email = $_SESSION['email'];
                    $sql = "UPDATE tbl_users SET user_password='$submittedPassword' WHERE user_email='$email'";
                    if ($conn->query($sql)) {
                        echo "<script>alert('Your username and password are reset.');window.location='logout.php?f=1';</script>";
                    }
                } else {
                    // OTP verification failed
                    echo "<script>alert('Entered OTP is Incorrect');</script>";
                    // Handle the case where the OTP is incorrect
                }

                // Clear the stored OTP from the session
                unset($_SESSION['generated_otp']);
                unset($_SESSION['submittedPassword']);
            }

    ?>


<body class="w3-light-grey">
    
            <!-- Navbar Start -->
            <nav class="bg-white navbar navbar-expand-lg navbar-light shadow-sm px-5 py-3 py-lg-0">
                <a href="index.php" class="navbar-brand p-0">
                    <h1 class="m-0 text-primary">
                        <i class="fa fa-tooth me-2"></i>Smile
                        <span style="color: orange">32</span>
                    </h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="index.php#about" class="nav-item nav-link">About Us</a>
                        <a href="index.php#services" class="nav-item nav-link">Service</a>
                        <a href="index.php#dentist" class="nav-item nav-link">Our Dentist</a>
                        <!-- <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
          <div class="dropdown-menu m-0">
            <a href="price.php" class="dropdown-item">Pricing Plan</a>
            <a href="team.php" class="dropdown-item">Our Dentist</a>
            <a href="testimonial.php" class="dropdown-item">Testimonial</a>
            <a href="appointment.php" class="dropdown-item">Appointment</a>
          </div>
        </div>-->
                        <?php if (isset($_SESSION['user']) && $_SESSION['user'] == 'user') { ?>



                            <a href="user-appointment.php" class="nav-item nav-link">Appointments</a>
                        <?php } elseif (isset($_SESSION['d']) && $_SESSION['user'] == 'd') { ?>
                            <a href="doctor-patients.php?id=<?= 5 ?>" class="nav-item nav-link">Patients</a>
                        <?php  } else { ?>
                            <a href="user-appointment.php" class="nav-item nav-link">Appointments</a>
                        <?php  } ?>






                    </div>
                    <?php if (isset($_SESSION['name'])) { ?>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><img src="img/person.png" alt="icon" class="icon" /></a>
                            <div class="dropdown-menu m-0">
                                <a href="User.php" class="dropdown-item"><?php echo $_SESSION['name'] ?></a>
                                <a href="logout.php" class="dropdown-item">SignOut</a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <a href="signup.php" class="btn btn-primary py-2 px-4 ms-3">login/Sign UP</a>
                    <?php } ?>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user'] == 'user') { ?>
                        <a href="user-appointment.php" class="btn btn-primary py-2 px-4 ms-3">Appointment</a>
                    <?php } ?>
                </div>
            </nav>
            <!-- Navbar End -->
            <?php if ($vali == '1') { ?>
                <div class='modal-content'>
                    <span class='close'>&times;</span>
                    <form id='otp-form' action='<?php echo $_SERVER["PHP_SELF"]; ?>' method='post'>
                        <label for='otp-input'>Please enter the OTP sent to your email:</label>
                        <input type='number' id="otp-input" name='otpinput'>
                        <input type='submit' value='Submit OTP' name='submit_otp'>
                    </form>
                </div>;
            <?php } ?>





            <!-- Page Container -->
            <div class="w3-content w3-margin-top" style="max-width:1400px;">

                <!-- The Grid -->
                <div class="w3-row-padding">

                    <!-- Left Column -->
                    <div class="w3-third" style="width:30%;">

                        <div class="w3-white w3-text-grey w3-card-4 ">
                            <div class="d-flex justify-content-center w3-display-container">
                                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                                    <label for="fileInput" class="file-label">
                                        <?php if (!empty($patient['profile_picture'])) { ?>
                                            <img src="<?php echo $patient['profile_picture'] ?>" style="margin-left: 41px; width: 70%" alt="Avatar" class="img-fluid rounded-circle">
                                        <?php } else { ?>
                                            <img src="img/person.png" style="margin-left: 41px; width: 70%" alt="Avatar" class="img-fluid rounded-circle">
                                        <?php } ?>
                                    </label>
                                    <input type="file" id="fileInput" name="profile_image" class="file-input" accept="image/*">
                                    <input type="submit" value="Change" name="profile" style="margin-left: 173px;">
                                </form>



                            </div>



                            <div class="w3-container">
                                <div class="w3-display-bottomleft w3-container w3-text-black">
                                    <div>

                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <h2 class=" w3-margin-right w3-large "><?php echo $patient['full_name']; ?></h2>
                                </div>
                                <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i>DOB:<?php echo $patient['date_of_birth']; ?></p>
                                <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i>Gender:<?php echo $patient['gender']; ?></p>
                                <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i><?php echo $_SESSION['email']; ?></p>
                                <p><i class=" fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i><?php echo $patient['emergency_contact_phone']; ?></p>
                                <hr>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <p class="w3-large"><b><i class="fa fa-asterisk fa-fw w3-margin-right w3-text-teal"></i>Reset Username and Password </b></p>
                                    <p>Email</p>
                                    <div class=" w3-round-xlarge w3-small">
                                        <input type="text" class="form-control bg-light border-0" id="username" value="<?php echo $_SESSION['email'] ?>" name="email" required readonly>
                                        <br><br>
                                    </div>
                                    <p>Password</p>
                                    <div class=" w3-round-xlarge w3-small">
                                        <input type="password" class="form-control bg-light border-0" value="<?php echo $_SESSION['password'] ?>" id="password" name="password" required><br><br>
                                    </div>

                                    <br>
                                    <div style=" width: 152px; margin-left: 116px; margin-top: -12px;">
                                        <input type="submit" class="btn btn-dark w-100 py-3" value="Update" name="reset_password">
                                    </div>
                                </form>
                            </div>
                        </div><br>
                        <?php if (isset($_GET['pay']) && $_GET['pay'] == 1) {

                        ?>
                            <div id="paymentModal" class="modal">
                                <div class="modal-content">
                                    <h2>Payment Form</h2>
                                    <a href="User.php" style="width: 10%;margin-left: 111vh;margin-top: -17px;position: absolute;"> <span class="close-button">&times;</span></a>

                                    <?php $appmtes = getAppointmentDetail($conn, $patient_id);
                                    foreach ($appmtes as $index => $appm) : ?>
                                        <label for="price">Amount To Pay:</label>
                                        <?php echo "₹" . $appm['paid_amount']; 
                                        $_SESSION['s_id']='5';?><br>

                                        
                                        <center> <a href="payment.php?payamount=<?php echo $appm['paid_amount'] ?>" class="btn btn-dark" style="width:10%;">PAY</a></center>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        <?php  } ?>
                        <script>
                            // JavaScript to display the modal when the page loads
                            window.addEventListener('DOMContentLoaded', function() {
                                <?php if (isset($_GET['pay']) && $_GET['pay'] == 1) { ?>
                                    var modal = document.getElementById('paymentModal');
                                    modal.style.display = 'block';
                                <?php } ?>
                            });
                        </script>



                        <!-- End Left Column -->
                    </div>

                    <!-- Right Column -->
                    <div class="w3-twothird">
                        <h2>User Appointmet Details</h2>
                        <table class="table table-success table-striped" style="font-size: smaller;">
                            <thead>
                                <tr>


                                    <th>Patient Email</th>
                                    <th>doctor Name</th>
                                    <th>service Name</th>

                                    <th>Next appointment</th>
                                    <th>Package Name</th>
                                    <th>Total Amount </th>
                                    <th>Due Amount</th>
                                    <th>Pay</th>
                                    <th>Appointment Status</th>



                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php

                                $appmts = getAppointmentDetails($conn, $patient_id);
                                if (!empty($appmts)) {
                                    foreach ($appmts as $index => $appmt) : ?>
                                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">

                                            <?php
                                            global $pay_amount;
                                            $pay_amount = $appmt['paid_amount'];
                                            $_SESSION['appointment_id'] = $appmt['appointment_id'];
                                            $p_id = $appmt['patient_id'];
                                            $names = fetchName($conn, $p_id, 'patient_id', "tbl_patient");
                                            /* foreach ($names as $index => $name) :
                                                    echo $name['full_name'];
                                                    $fullname = $name['full_name'];
                                                endforeach;*/
                                            ?>
                                            <td><?php
                                                $p_email = $appmt['patient_email'];
                                                echo $p_email;
                                                ?></td>
                                            <td><?php
                                                $d_id = $appmt['doctor_id'];
                                                $d_names = fetchName($conn, $d_id, 'doctor_id', "tbl_doctors");
                                                foreach ($d_names as $index => $d_name) :
                                                    echo $d_name['doctor_name'];
                                                    $d = $d_name['doctor_name'];
                                                endforeach;
                                                ?></td>
                                            <td><?php
                                                $s_id = $appmt['service_id'];


                                                $s_names = fetchName($conn, $s_id, 'service_id', "tbl_services");

                                                foreach ($s_names as $index => $s_name) :
                                                    $s = $s_name['service_name'];
                                                    echo $s;
                                                endforeach;

                                                ?></td>

                                            <td><?php
                                                $section = $appmt['section'];
                                                $appo_time = $appmt['appo_time'];
                                                $appo_date = $appmt['appointmentneed_date'];
                                                echo $section . '<br>';
                                                echo '<span style="font-size: xx-small;">' . $appo_time . '</span><br>';
                                                echo $appo_date . '<br>';
                                                ?></td>


                                            <td><?php
                                                $package_id = $appmt['package_id'];
                                                $package_names = fetchName($conn, $package_id, 'package_id', "tbl_price_packages");
                                                foreach ($package_names as $index => $package_name) :
                                                    echo $package_name['package_name'];
                                                endforeach;
                                                ?></td>
                                            <td><?php
                                                foreach ($package_names as $index => $package_name) :
                                                    echo $package_name['price'];
                                                    $_SESSION['pack_price'] = $package_name['price'];
                                                endforeach;
                                                ?></td>
                                            <td><?php
                                                $due = $appmt['due_amount'];
                                                echo $due;
                                                //echo '<a href="User.php?pay=1" class="btn btn-info">Pay Now</a>';
                                                ?></td>
                                            
                                            <td><?php
                                                if ($appmt['due_amount'] == '0') {
                                                    echo 'Completed';
                                                } else {
                                                    echo '<a href="User.php?pay=1" class="btn btn-info">Pay Now</a>';
                                                }
                                                ?></td>
                                                <td><?php
                                                $status = $appmt['status'];
                                                echo $status;
                                                //echo '<a href="User.php?pay=1" class="btn btn-info">Pay Now</a>';
                                                ?></td>

                    </div>
                <?php endforeach; ?>

                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="10">No appointment on selected date</td>
                </tr>

            <?php } ?>

            </tbody>


            </table>
                </div>
                <div class="w3-twothird">

                    <?php foreach ($patients as $index => $patient) : ?>

                        <div class="w3-container w3-card w3-white w3-margin-bottom">
                            <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Patient Details</h2>
                            <div class="w3-container">
                                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                                    <div class="flex">
                                        <label for="full_name">Full Name:</label>
                                        <input type="text" class="form-control bg-light border-0" value="<?php echo $patient['full_name']; ?>" id="full_name" name="full_name" required><br><br>
                                    </div>
                                    <div class="flex">
                                        <label for="gender">Gender:</label>
                                        <select id="gender" value="<?php echo $patient['gender']; ?>" class="form-select bg-light border-0" name="gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select><br><br>
                                    </div>

                                    <label for="date_of_birth">Date of Birth:</label>
                                    <input type="date" class="form-select bg-light border-0" value="<?php echo $patient['date_of_birth']; ?>" id="date_of_birth" name="date_of_birth" required><br><br>

                                    <label for="address">Address:</label>
                                    <input type="text" class="form-select bg-light border-0" value="<?php echo $patient['address']; ?>" id="address" name="address" size="50"><br><br>

                                    <label for="profile_picture">Profile Picture:</label>
                                    <input type="file" class="form-select bg-light border-0" id="profile_picture" value="<?php echo $patient['profile_picture']; ?>" name="profile_picture"><br><br>
                                    <label for="emergency_contact_phone">Emergency Contact Phone:</label>
                                    <input type="text" id="emergency_contact_phone" value="<?php echo $patient['emergency_contact_phone']; ?>" name="phone" oninput="checkPhoneNumber()">
                                    <span id="phoneMessage" style="color: red;"></span><br><br>

                                    <div id="phoneAlert" class="alert  alert-warning alert-dismissible fade show" role="alert" style="display: none;">
                                        <strong>Phone number</strong> should only contain 10 digits.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>

                                    


                                    <div id="allergy_input" >
                                        <label for="allergy_info">Type Allergy Info:</label>
                                        <input class="form-select bg-light border-0" value="<?php echo $patient['allergy_info']; ?>" type="text" id="allergy_info" name="allergy_info" size="50"><br><br>
                                    </div>




                                  

                                    



                                    <input type="submit" name="insert" value="Update Details ">
                                </form>
                            <?php endforeach; ?>

                            <hr>
                            </div>


                            <!-- End Right Column -->
                        </div>

                        <!-- End Grid -->
                </div>

                <!-- End Page Container -->
            </div>
        <?php } ?>
        <div style="margin-top:60px;">
            <?php include('footer.php'); ?>
        </div>
    <?php } ?>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="lib/twentytwenty/jquery.event.move.js"></script>
    <script src="lib/twentytwenty/jquery.twentytwenty.js"></script>

    <script>
        // JavaScript to display the modal when the link is clicked
        var modal = document.getElementById('paymentModal');
        var link = document.querySelector('.btn-info');

        link.onclick = function() {
            modal.style.display = 'block';
        };
    </script>

    <script>
        function checkPhoneNumber() {
            const phoneNumberInput = document.getElementById('emergency_contact_phone');
            const phoneMessage = document.getElementById('phoneMessage');
            const phoneAlert = document.getElementById('phoneAlert');
            const phoneNumber = phoneNumberInput.value.replace(/\D/g, '');
            if (phoneNumberInput.value.length > 10 || phoneNumberInput.value.length < 10) {
                phoneMessage.textContent = '';
                phoneAlert.style.display = 'block';
            } else {
                phoneMessage.textContent = '';
                phoneAlert.style.display = 'none';
            }
        }


        const loginText = document.querySelector(".title-text .login");
        const loginForm = document.querySelector("form.login");
        const loginBtn = document.querySelector("label.login");
        const signupBtn = document.querySelector("label.signup");
        const signupLink = document.querySelector("form .signup-link a");
        signupBtn.onclick = (() => {
            loginForm.style.marginLeft = "-50%";
            loginText.style.marginLeft = "-50%";
        });
        loginBtn.onclick = (() => {
            loginForm.style.marginLeft = "0%";
            loginText.style.marginLeft = "0%";
        });
        signupLink.onclick = (() => {
            signupBtn.click();
            return false;
        });
    </script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>