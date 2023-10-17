<?php
session_start();



$timeSlots = "";
include("connection.php");
function fetchTableData($conn, $tableName)
{
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

$doctors = fetchTableData($conn, "tbl_doctors");
$services = fetchTableData($conn, "tbl_services");
function userData($user)
{
    global $conn;
    $sql = "SELECT user_email FROM tbl_users WHERE user_username = '$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        $email = $row['user_email'];
    }
    return $email;
}
function userId($user)
{
    global $conn;
    $sql = "SELECT user_id FROM tbl_users WHERE user_username = '$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        $id = $row['user_id'];
    }
    return $id;
}
//if user not logged in then 

if (isset($_SESSION['name'])) {
    global $userId;
    $User = $_SESSION['name'];
    $email = userData($User);
    $userId = userId($User);

    $sql = "SELECT user_id
    FROM  tbl_patient WHERE user_id = $userId";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        $flag = true;
    } else {
        $flag = false;
        echo $flag;
    }
} else {
    echo '<script>
    if (confirm("User not logged in. Do you want to go to the signup page?")) {
        window.location.href = "signup.php";
    } else {
        window.location.href = "index.html"; // Redirect to index.php if Cancel is clicked
    }
</script>';
    exit; // Stop further PHP execution

    // Submit patient details
}
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['details']))) {
    // Retrieve form data
    $name = $_POST["name"];
    $phoneNumber = $_POST["phoneNumber"];
    $dateOfBirth = $_POST["dateOfBirth"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $allergy = $_POST["allergy"];
    $User = $_SESSION['name'];
    $email = userData($User);
    $userId = userId($User);

    $sql = "SELECT user_id
    FROM  tbl_patient WHERE user_id = $userId";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        echo '<script>
    if (confirm("Useralready enter these Details")) {
        window.location.href = "user-appointment.php";
    }</script>';
    } else {

        $sql = "INSERT INTO tbl_patient (user_id,full_name, gender, date_of_birth, address, allergy_info,emergency_contact_phone) 
    VALUES (?,?, ?, ?, ?, ?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $userId, $name, $gender, $dateOfBirth, $address, $allergy, $phoneNumber);



        if ($stmt->execute()) {
            echo '<script>
var confirmed = confirm("Submit Your Details added successfully. Click OK to continue.");
if (confirmed) {
window.location.href = "user-appointment.php";
}
</script>';
        } else {
            echo "Error inserting data: " . $stmt->error;
        }


        $stmt->close();
        $conn->close();
    }
}
/*if ((isset($_POST['book_now']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))) {
    $serviceId = $_POST['service_id'];
    $doctorId = $_POST['doctor_id'];
    $section = $_POST['section'];
    $appointmentDate = $_POST['appointmentDate'];

    $selectedTimeSlot = $_SESSION['selectedTimeSlot'];
    $user = $_SESSION['name'];

    $userId = userId($user);
    echo $userId;
    $sql = "SELECT * FROM tbl_patient WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If there are rows in the result, fetch and use the data here
        while ($row = $result->fetch_assoc()) {
            // Access data from the row
            $patient_id = $row['patient_id'];
            $email = $_SESSION['email'];
            $sql = "INSERT INTO tbl_appointments (patient_id, doctor_id,patient_email, service_id,section,appo_time, status, appointmentneed_date, created_at)
            VALUES (?, ?,?, ?,?,?, 'pending', ?, NOW())";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bind_param("iisssss", $patient_id, $doctorId, $email, $serviceId, $section, $selectedTimeSlot, $appointmentDate);

            // You may need to determine the patient_id based on the email or other criteria.
            // For this example, I'm assuming you have a patients table with an email column.



            $stmt2->execute();

            $result = $stmt2->get_result();

            if ($result->num_rows > 0) {


                $response = "Appointment booked successfully!";
            } else {
                $response = "Error: Patient not found.";
            }

            // Close the database connection
            $stmt->close();
            $stmt2->close();
            $conn->close();
        }
    }

    // Perform any necessary validation on the data here






    // Prepare and execute the SQL INSERT query
}*/

$selectedTimeSlot = '';
if (isset($_POST['selectedTimeSlot'])) {
    // Handle the selected time slot if it's received from a previous form submission
    $selectedTimeSlot = $_POST['selectedTimeSlot'];
    echo $selectedTimeSlot;

    // You can perform server-side processing with the selectedTimeSlot here
    // For demonstration purposes, we'll simply store it in a variable
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Smile 32</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon njjn-->
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
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-dark m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-secondary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-light ps-5 pe-0 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center">
                    <small class="py-2"><i class="far fa-clock text-primary me-2"></i>Opening Hours: Mon - Tues : 6.00 am - 10.00 pm, Sunday Closed </small>
                </div>
            </div>
            <div class="col-md-6 text-center text-lg-end">
                <div class="position-relative d-inline-flex align-items-center  text-white top-shape px-5" style="background-color: #06A3DA !important;">
                    <div class="me-3 pe-3 border-end py-2">
                        <p class="m-0"><i class="fa fa-envelope-open me-2"></i>info@example.com</p>
                    </div>
                    <div class="py-2">
                        <p class="m-0"><i class="fa fa-phone-alt me-2"></i>+012 345 6789</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


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
                <a href="index.html" class="nav-item nav-link">Our Dentist</a>
                <a href="index.html" class="nav-item nav-link">Appointment</a>
                <!--<div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu m-0">
                        <a href="price.php" class="dropdown-item active">Pricing Plan</a>
                        <a href="team.php" class="dropdown-item">Our Dentist</a>
                        <a href="testimonial.php" class="dropdown-item">Testimonial</a>

                        <a href="appointment.php" class="dropdown-item">Appointment</a>
                    </div>
                </div>-->
                <!--<a href="index.html" class="nav-item nav-link active">Contact</a>-->
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


    <!-- Full Screen Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
                <div class="modal-header border-0">
                    <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <div class="input-group" style="max-width: 600px;">
                        <input type="text" class="form-control bg-transparent border-primary p-3" placeholder="Type search keyword">
                        <button class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Screen Search End -->


    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 hero-header mb-5">
        <div class="row py-3">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white animated zoomIn">Appointment</h1>
                <a href="index.html" class="h4 text-white">Home</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="user-appointment.php" class="h4 text-white">Appointment</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Contact Start -->
    <!--<div class="container-fluid py-5" style="background-color: #091e3e">-->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-xl-4 col-lg-6 wow slideInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded h-100 p-5">
                        <div class="section-title">
                            <h5 class="position-relative d-inline-block text-primary text-uppercase">Contact Us</h5>
                            <h1 class="display-6 mb-4">Feel Free To Contact Us</h1>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-geo-alt fs-1 text-primary me-3"></i>
                            <div class="text-start">
                                <h5 class="mb-0">Our Clinic</h5>
                                <span>Tripunithura. 3.0. Layam Road, Tripunithura, Ernakulam - 682301 (Near Chakkankulangara Temple).</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-envelope-open fs-1 text-primary me-3"></i>
                            <div class="text-start">
                                <h5 class="mb-0">Email Us</h5>
                                <span>Abc@gmail.com.com</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-phone-vibrate fs-1 text-primary me-3"></i>
                            <div class="text-start">
                                <h5 class="mb-0">Call Us</h5>
                                <span>094461 80415</span><br>
                                <span>0484 277 7924</span><br>
                                <span>094961 79353</span><br>


                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($flag) { ?>
                    <div class="col-xl-4 col-lg-6 wow slideInUp a-cont" data-wow-delay="0.3s">
                        <div style="color:#fff;font-size: x-large;" class="row justify-content-center text-uppercase ">
                            <center>
                                <h1 style="color: #fff;"><u>Book appointment</u></h1>
                            </center>
                        </div>

                        <form>
                            <div class="row g-3" style="margin-top: 4px;">
                                <!-- <div class="col-12  ">
                                <input type="text" class="form-control border-0 bg-light px-4" placeholder="Your Name" style="height: 55px;" required>
                            </div>
                            <div class="col-12 col-sm-6 ">
                                <input type="email" class="form-control border-0 bg-light px-4" value="<?php echo $email; ?>" placeholder="Your Email" style="height: 55px;" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="number" class="form-control border-0 bg-light px-4" placeholder="Your Phone Number" style="height: 55px;" id="phoneNumber" required>
                                <div id="phoneError" class="text-danger" style="transform: rotate(360deg);animation: rotation 5s linear infinite;"></div>
                            </div>-->

                                <div class="col-12 col-sm-6 ">
                                    <select class="form-select bg-light border-0" name="service_id" style="height:54px;" onchange="fetchAvailableTimeSlots()" required>
                                        <option value="default" selected>Choose Service</option>
                                        <?php
                                        foreach ($services as $index => $service) :

                                            $service_id = $service["service_id"];
                                            $service_name = $service["service_name"];
                                            echo "<option  value=\"$service_id\" >$service_name</option>";


                                        endforeach;


                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <select class="form-select bg-light border-0" name="doctor_id" style="height:54px;" onchange="fetchAvailableTimeSlots()" required>
                                        <option value="default" selected>Choose Doctor</option>
                                        <?php
                                        foreach ($doctors as $index => $doctor) :

                                            $doctor_id = $doctor["doctor_id"];
                                            $doctor_name = $doctor["doctor_name"];
                                            echo "<option  value=\"$doctor_id\" >$doctor_name</option>";


                                        endforeach;


                                        ?>
                                    </select>
                                </div>
                                <div class="row  t-section">
                                    <div class="col-12 col-sm-6  " style="color:#fff">
                                        <input type="radio" name="section" id="morning" value="morning" onchange="fetchAvailableTimeSlots()" required>
                                        <label for="morning">Morning 09:00 AM - 12:00 PM</label>
                                    </div>
                                    <div class="col-12 col-sm-6 " style="color:#fff">
                                        <input type="radio" name="section" id="Afternoon" value="afternoon" onchange="fetchAvailableTimeSlots()" required>
                                        <label for="Afternoon">Afternoon 12:00 PM - 02:50 PM</label>
                                    </div>
                                    <div class=" col-sm-6 l2">
                                        <input type="radio" name="section" id="Evening" value="evening" onchange="fetchAvailableTimeSlots()" required>
                                        <label for="Evening" style="margin-right:-5px;" class="l">Evening 04:20 PM - 05:00 PM</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 l2">
                                    <label for="date" class="text-uppercase">Choose needed date :</label>
                                    <div>
                                        <input type="date" id="appointmentDate" name="appointmentDate" style="margin-top:3px;" class="form-control border-0 bg-light px-4" min="" required onchange="fetchAvailableTimeSlots()">
                                    </div>


                                </div>


                                <!-- Time slots container -->

                                <div id="" class="grid-container">
                                    <div id="timeSlotsContainer" class="row">

                                    </div>
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <!--<script>
                                        // JavaScript code goes here
                                        $(document).on("click", ".time-slot-button", function() {
                                            var selectedTimeSlot = $(this).data("time-slot");

                                            // Send the selectedTimeSlot to the server using AJAX
                                            $.ajax({
                                                type: "POST",
                                                url: "user-appointment.php", // Replace with the name of this PHP file
                                                data: {
                                                    selectedTimeSlot: selectedTimeSlot
                                                },
                                                success: function(response) {
                                                    // Handle the response from the server (if needed)
                                                    console.log("Selected time slot sent to the server: " + selectedTimeSlot);
                                                },
                                                error: function() {
                                                    // Handle errors here if needed
                                                    console.error("An error occurred during the AJAX request.");
                                                }
                                            });
                                        });
                                    </script>-->
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <input type="submit" style="width:160px;margin-top:18px;" value="Book Now" class="btn btn-primary py-2 px-4 ms-3 " name="book_now">
                            </div>




                        </form>
                        <!--<form>
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" placeholder="Your Name" style="height: 55px;">
                            </div>
                            <div class="col-12 col-sm-6">
                            <select class="form-select bg-light border-0" name="doctor_id" style="height:54px;" required>
                      <?php
                        foreach ($services as $index => $service) :

                            $service_id = $service["service_id"];
                            $service_name = $service["service_name"];
                            echo "<option  value=\"$service_id\" selected>$service_name</option>";


                        endforeach;


                        ?>
                    </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <select class="form-select bg-light border-0" style="height: 55px;">
                                    <option selected>Select Doctor</option>
                                    <option value="1">Doctor 1</option>
                                    <option value="2">Doctor 2</option>
                                    <option value="3">Doctor 3</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <input type="email" class="form-control border-0 bg-light px-4" placeholder="Your Email" style="height: 55px;">
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" placeholder="Subject" style="height: 55px;">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control border-0 bg-light px-4 py-3" rows="5" placeholder="Message"></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>-->
                        <!--<div class="col-xl-4 col-lg-12 wow slideInUp" data-wow-delay="0.6s">
                    <iframe class="position-relative rounded w-100 h-100" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001156.4288297426!2d-78.01371936852176!3d42.72876761954724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4ccc4bf0f123a5a9%3A0xddcfc6c1de189567!2sNew%20York%2C%20USA!5e0!3m2!1sen!2sbd!4v1603794290143!5m2!1sen!2sbd" frameborder="0" style="min-height: 400px; border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    <iframe class="position-relative rounded w-100 h-100" src="https://www.google.com/maps/dir//Smile+32+Dental+Clinic/@9.9485722,76.2634294,12z/data=!4m8!4m7!1m0!1m5!1m1!1s0x3b08736c06ffece7:0x6e9419692f18feb3!2m2!1d76.3458313!2d9.9485823?entry=ttu" frameborder="0" style="min-height: 400px; border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div> -->
                    </div>
                <?php } else { ?>
                    <div class="appointment-form h-100 d-flex flex-column justify-content-center text-center p-5 wow zoomIn" style="width:66%" data-wow-delay="0.6s">
                        <h1 class="text-white mb-4">Submit Your Details</h1>
                        <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="post">
                            <div class="row g-3">
                                <!-- <div class="col-12 col-sm-6">
                                    <select class="form-select bg-light border-0" style="height: 55px;">
                                        <option selected>Select A Service</option>
                                        <option value="1">Service 1</option>
                                        <option value="2">Service 2</option>
                                        <option value="3">Service 3</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <select class="form-select bg-light border-0" style="height: 55px;">
                                        <option selected>Select Doctor</option>
                                        <option value="1">Doctor 1</option>
                                        <option value="2">Doctor 2</option>
                                        <option value="3">Doctor 3</option>
                                    </select>
                                </div>-->
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="form-control bg-light border-0" placeholder="Your Name" style="height: 55px;" name="name">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="number" class="form-control border-0 bg-light px-4" name="phoneNumber" placeholder="Your Phone Number" style="height: 55px;" id="phoneNumber" required>
                                    <div id="phoneError" class="text-danger" style="transform: rotate(360deg);animation: rotation 5s linear infinite;"></div>
                                </div>
                                <div class="row" style="color: #fff;">
                                    <label for="" style="width: 160px;">Date Of Birth</label>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="date" class="form-control bg-light border-0" name="dateOfBirth" placeholder="Your " style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <select class="form-select bg-light border-0" name="gender" style="height: 55px;">
                                        <option selected>Male</option>
                                        <option value="">Female</option>
                                        <option value="">Other</option>

                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="date" id="" data-target-input="nearest">
                                        <!--<input type="textarea" class="form-control bg-light border-0 datetimepicker-input" placeholder="Enter Your Address" data-target="#date1" data-toggle="datetimepicker" style="height: 55px;">-->
                                        <textarea class="form-control bg-light border-0 datetimepicker-input" name="allergy" placeholder="Allergy Information" style="height: 55px;"></textarea>

                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="date" id="" data-target-input="nearest">
                                        <!--<input type="textarea" class="form-control bg-light border-0 datetimepicker-input" placeholder="Enter Your Address" data-target="#date1" data-toggle="datetimepicker" style="height: 55px;">-->
                                        <textarea class="form-control bg-light border-0 datetimepicker-input" name="address" placeholder="Enter Your Address" style="height: 55px;"></textarea>

                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-dark w-100 py-3" type="submit" name="details">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>

            </div>
        </div>
        <!-- Contact End -->


        <!-- Newsletter Start -->
        <div class="container-fluid position-relative pt-5 wow fadeInUp" data-wow-delay="0.1s" style="z-index: 1;">
           <!-- <div class="container">
                <div class="bg-primary p-5">
                    <form class="mx-auto" style="max-width: 600px;">
                        <div class="input-group">
                            <input type="text" class="form-control border-white p-3" placeholder="Your Email">
                            <button class="btn btn-dark px-4">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>-->
        </div>
        <!-- Newsletter End -->


        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-light py-5 wow fadeInUp" data-wow-delay="0.3s" style="margin-top: -75px;">
            <div class="container pt-5">
                <div class="row g-5 pt-4">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Quick Links</h3>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                            <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                            <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                            <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                            <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Popular Links</h3>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                            <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                            <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                            <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                            <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Get In Touch</h3>
                        <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i>123 Street, New York, USA</p>
                        <p class="mb-2"><i class="bi bi-envelope-open text-primary me-2"></i>info@example.com</p>
                        <p class="mb-0"><i class="bi bi-telephone text-primary me-2"></i>+012 345 67890</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Follow Us</h3>
                        <div class="d-flex">
                            <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid text-light py-4" style="background: #051225;">
            <div class="container">
                <div class="row g-0">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-md-0">&copy; <a class="text-white border-bottom" href="#">Your Site Name</a>. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Designed by <a class="text-white border-bottom" href="">Aravindh sunilkumar</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>


        <!-- JavaScript Libraries -->
        <script>
            // Function to fetch and update time slots based on selected options
            function fetchAvailableTimeSlots() {
                // Gather selected values
                var serviceId = $("select[name='service_id']").val();
                var doctorId = $("select[name='doctor_id']").val();
                var section = $("input[name='section']:checked").val();
                var appointmentDate = $("#appointmentDate").val();

                // Send AJAX request to fetch data
                $.ajax({
                    type: "POST",
                    url: "update_status.php", // Replace with your PHP script URL
                    data: {
                        serviceId: serviceId,
                        doctorId: doctorId,
                        section: section,
                        appointmentDate: appointmentDate
                    },

                    success: function(response) {
                        // Handle the response from PHP here
                        // You can update the #timeSlotsContainer with the received data
                        $("#timeSlotsContainer").html(response);
                    },
                    error: function() {
                        // Handle errors here if needed
                        console.error("An error occurred during the AJAX request.");
                    }
                });
            }

            // Attach onchange event listeners to the relevant form elements
            $("select[name='service_id']").change(fetchAvailableTimeSlots);
            $("select[name='doctor_id']").change(fetchAvailableTimeSlots);
            $("input[name='section']").change(fetchAvailableTimeSlots);
            $("#appointmentDate").change(fetchAvailableTimeSlots);

            // Initial fetch when the page loads
            fetchAvailableTimeSlots();

            // JavaScript function to handle form submission
            function submitForm() {
                // Get form data
                // var name = document.querySelector('input[name="name"]').value;
                // var email = document.querySelector('input[name="email"]').value;
                //var phoneNumber = document.querySelector('input[name="phoneNumber"]').value;
                var serviceId = document.querySelector('select[name="service_id"]').value;
                var doctorId = document.querySelector('select[name="doctor_id"]').value;
                var section = document.querySelector('input[name="section"]:checked').value;
                var appointmentDate = document.querySelector('input[name="appointmentDate"]').value;
                var name = document.querySelector('input[name="book_now"]').value;
                var selectedTimeSlot = $("input[name='time']:checked").val();


                // Create a FormData object to send data as a POST request
                book = true;
                var formData = new FormData();
                //formData.append('name', name);
                //formData.append('email', email);
                //formData.append('phoneNumber', phoneNumber);
                formData.append('service_id', serviceId);
                formData.append('doctor_id', doctorId);
                formData.append('section', section);
                formData.append('appointmentDate', appointmentDate);
                formData.append('selectedTimeSlot', selectedTimeSlot);
                formData.append('book_now', name);

                var xhr = new XMLHttpRequest();

                // Define the POST request details
                xhr.open('POST', 'update_status.php', true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Handle the response from update_status.php here
                        var response = xhr.responseText;

                        // Display the response message in an alert
                        alert(response); // This will show the "Appointment booked successfully!" message
                        
                    }
                };

                // Send the POST request
                xhr.send(formData);
            }

            // Attach the submitForm function to the form's submit event
            document.querySelector('form').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
                submitForm(); // Call the submitForm function to handle the submission
            });
            // Assuming you have a function that handles the booking process and you want to reset the form after a successful booking
            function handleBookingSuccess() {
                // Clear all form inputs and reset them to their default values
                $("form")[0].reset();

                // Clear the #timeSlotsContainer if needed
                $("#timeSlotsContainer").html("");

                // You may also want to reset any other variables or UI elements as needed
            }

            // Call this function when the booking is successful
            handleBookingSuccess();
        </script>
        <!-- Add this JavaScript code to the HTML file where the buttons are displayed -->

        </script>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Get the input element and the error message element
                var phoneNumberInput = document.getElementById("phoneNumber");
                var phoneError = document.getElementById("phoneError");

                // Add an input event listener to the input field
                phoneNumberInput.addEventListener("input", function() {
                    // Get the entered value
                    var phoneNumber = phoneNumberInput.value;

                    // Remove any non-digit characters (e.g., spaces)
                    phoneNumber = phoneNumber.replace(/\D/g, '');

                    // Check if the length is exactly 10 digits
                    if (phoneNumber.length === 10) {
                        // Clear any previous error message
                        phoneError.textContent = "";
                    } else {
                        // Display an error message
                        phoneError.textContent = "Please enter a 10-digit phone number.";
                    }

                    // Limit the input to exactly 10 digits
                    if (phoneNumber.length > 10) {
                        phoneNumber = phoneNumber.substring(0, 10);
                        phoneNumberInput.value = phoneNumber;
                    }
                });
            });
        </script>
        <script>
            // Get the current date in yyyy-mm-dd format    
            const currentDate = new Date().toISOString().split('T')[0];
            // Set the minimum date for the input field to the current date
            document.getElementById("appointmentDate").min = currentDate;
        </script>
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

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
</body>

</html>