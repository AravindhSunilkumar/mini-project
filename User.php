<?php
session_start();
include('connection.php');
// Include the message.php file
include('message.php');
$vali = '2';
$otp = 0;

$username = $_SESSION['name'];
$password = $_SESSION['password'];
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
    </style>
</head>

<body class="w3-light-grey">
    <?php if ($_SESSION['name'] === 'admin') {
        header('location:admin_menu.php');
    } else {
        foreach ($patients as $index => $patient) {



            // Function to generate a random OTP (replace with your implementation)
            function generateRandomOTP()
            {
                // Generate and return a random OTP
                return rand(1000, 9999);
            }

            if (isset($_POST['reset_password'])) {


                // Get the submitted username and password
                $submittedUsername = $_POST['username'];
                $submittedPassword = $_POST['password'];

                // Get the stored username and password from the session
                $storedUsername = $_SESSION['name'];
                $storedPassword = $_SESSION['password'];

                // Initialize $v as false to indicate whether the OTP was verified


                // Check if the submitted username and password match the stored values
                if ($submittedUsername !== $storedUsername || $submittedPassword !== $storedPassword) {
                    // No changes, display confirmation box with OTP input field
                    $otp = generateRandomOTP(); // Generate a random OTp
                    echo $otp;
                    // Send the OTP via email
                    email($_SESSION['email'], 'OTP Verification for changing password / username' . $otp . '', 'Your OTP is: ' . $otp);
                    $vali = '1';
                } else {
                    // Changes detected, you can perform actions or redirect to a different page
                    // For example, you can update the username and password here
                    // Redirect to a different page
                    //header("Location: update_password.php");
                    // exit();
                }
            }

            // Check the OTP form submission
            if (isset($_POST['submit_otp'])) {
                $enteredOTP = $_POST['otp-input'];

                // Check the entered OTP against the generated OTP
                if ($enteredOTP === $otp) {
                    // OTP verification succeeded
                    // Perform the SQL update only if OTP was verified
                    $sql = "UPDATE tbl_users SET user_username='$submittedUsername', user_password='$submittedPassword'";
                    if ($conn->query($sql)) {
                        echo "<script>alert('Your username and password are reset.');</script>";
                    }
                } else {
                    // OTP verification failed
                    echo "<script>alert('Entered OTP is Incorrect');</script>";
                    // Handle the case where the OTP is incorrect
                }
            }

    ?>
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-5 py-3 py-lg-0">
                <a href="contact.php" class="navbar-brand p-0">
                    <h1 class="m-0 text-primary"><i class="fa fa-tooth me-2"></i>Smile <span style="color:orange;">32</span></h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="index.html" class="nav-item nav-link">Home</a>
                        <a href="index.html" class="nav-item nav-link">About</a>
                        <a href="index.html" class="nav-item nav-link">Service</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-0">
                                <a href="price.php" class="dropdown-item active">Pricing Plan</a>
                                <a href="team.php" class="dropdown-item">Our Dentist</a>
                                <a href="testimonial.php" class="dropdown-item">Testimonial</a>

                                <a href="appointment.php" class="dropdown-item">Appointment</a>
                            </div>
                        </div>
                        <a href="index.html" class="nav-item nav-link active">Contact</a>
                    </div>
                    <?php if (isset($_SESSION['name'])) { ?>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><img src="img/person.png" alt="icon" class="icon"></a>
                            <div class="dropdown-menu m-0">
                                <a href="User.php" class="dropdown-item"><?php echo $_SESSION['name'] ?></a>
                                <a href="logout.php" class="dropdown-item">SignOut</a>

                            </div>
                        </div>
                    <?php } else { ?>
                        <a href="signup.php" class="btn btn-primary py-2 px-4 ms-3">login/Sign UP</a>
                    <?php } ?>
                    <a href="appointment.html" class="btn btn-primary py-2 px-4 ms-3">Appointment</a>
                </div>
            </nav>
            <!-- Navbar End -->
            <?php if ($vali == '1') { ?>
                <div class='modal-content'>
                    <span class='close'>&times;</span>
                    <form id='otp-form' action='<?php echo $_SERVER["PHP_SELF"]; ?>' method='post'>
                        <label for='otp-input'>Please enter the OTP sent to your email:</label>
                        <input type='text' id="otp-input" name='otp-input'>
                        <input type='submit' value='Submit OTP' name='submit_otp'>
                    </form>
                </div>;
            <?php } ?>





            <!-- Page Container -->
            <div class="w3-content w3-margin-top" style="max-width:1400px;">

                <!-- The Grid -->
                <div class="w3-row-padding">

                    <!-- Left Column -->
                    <div class="w3-third">

                        <div class="w3-white w3-text-grey w3-card-4 ">
                            <div class="d-flex justify-content-center    w3-display-container">
                                <img src="img/person.png" style="width:70%" alt="Avatar" class="img-fluid rounded-circle">



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
                                    <p>Username</p>
                                    <div class=" w3-round-xlarge w3-small">
                                        <input type="text" class="form-control bg-light border-0" id="username" value="<?php echo $_SESSION['name'] ?>" name="username" required><br><br>
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

                        <!-- End Left Column -->
                    </div>

                    <!-- Right Column -->
                    <div class="w3-twothird">

                        <div class="w3-container w3-card w3-white w3-margin-bottom">
                            <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Patient Details</h2>
                            <div class="w3-container">
                                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                                    <div class="flex">
                                        <label for="full_name">Full Name:</label>
                                        <input type="text" class="form-control bg-light border-0" id="full_name" name="full_name" required><br><br>
                                    </div>
                                    <div class="flex">
                                        <label for="gender">Gender:</label>
                                        <select id="gender" class="form-select bg-light border-0" name="gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select><br><br>
                                    </div>

                                    <label for="date_of_birth">Date of Birth:</label>
                                    <input type="date" class="form-select bg-light border-0" id="date_of_birth" name="date_of_birth" required><br><br>

                                    <label for="address">Address:</label>
                                    <input type="text" class="form-select bg-light border-0" id="address" name="address" size="50"><br><br>

                                    <label for="profile_picture">Profile Picture:</label>
                                    <input type="file" class="form-select bg-light border-0" id="profile_picture" name="profile_picture"><br><br>

                                    <label>Allergy Info:</label>


                                    <div id="allergy_input" style="display: none;">
                                        <label for="allergy_info">Type Allergy Info:</label>
                                        <input class="form-select bg-light border-0" type="text" id="allergy_info" name="allergy_info" size="50"><br><br>
                                    </div>



                                    <label for="emergency_contact_phone">Emergency Contact Phone:</label>
                                    <input type="text" id="emergency_contact_phone" name="phone" oninput="checkPhoneNumber()">
                                    <span id="phoneMessage" style="color: red;"></span><br><br>

                                    <div id="phoneAlert" class="alert  alert-warning alert-dismissible fade show" role="alert" style="display: none;">
                                        <strong>Phone number</strong> should only contain 10 digits.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>



                                    <input type="submit" name="insert" value="Add Patient ">
                                </form>

                                <hr>
                            </div>
                            <div class="w3-container">
                                <h5 class="w3-opacity"><b>Web Developer / something.com</b></h5>
                                <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>Mar 2012 - Dec 2014</h6>
                                <p>Consectetur adipisicing elit. Praesentium magnam consectetur vel in deserunt aspernatur est reprehenderit sunt hic. Nulla tempora soluta ea et odio, unde doloremque repellendus iure, iste.</p>
                                <hr>
                            </div>
                            <div class="w3-container">
                                <h5 class="w3-opacity"><b>Graphic Designer / designsomething.com</b></h5>
                                <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>Jun 2010 - Mar 2012</h6>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p><br>
                            </div>
                        </div>

                        <div class="w3-container w3-card w3-white">
                            <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Education</h2>
                            <div class="w3-container">
                                <h5 class="w3-opacity"><b>W3Schools.com</b></h5>
                                <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>Forever</h6>
                                <p>Web Development! All I need to know in one place</p>
                                <hr>
                            </div>
                            <div class="w3-container">
                                <h5 class="w3-opacity"><b>London Business School</b></h5>
                                <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2013 - 2015</h6>
                                <p>Master Degree</p>
                                <hr>
                            </div>
                            <div class="w3-container">
                                <h5 class="w3-opacity"><b>School of Coding</b></h5>
                                <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2010 - 2013</h6>
                                <p>Bachelor Degree</p><br>
                            </div>
                        </div>

                        <!-- End Right Column -->
                    </div>

                    <!-- End Grid -->
                </div>

                <!-- End Page Container -->
            </div>
        <?php } ?>

        <footer class="w3-container w3-teal w3-center w3-margin-top">
            <p>Find me on social media.</p>
            <i class="fa fa-facebook-official w3-hover-opacity"></i>
            <i class="fa fa-instagram w3-hover-opacity"></i>
            <i class="fa fa-snapchat w3-hover-opacity"></i>
            <i class="fa fa-pinterest-p w3-hover-opacity"></i>
            <i class="fa fa-twitter w3-hover-opacity"></i>
            <i class="fa fa-linkedin w3-hover-opacity"></i>
            <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>

        </footer>
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