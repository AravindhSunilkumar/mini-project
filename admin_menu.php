<?php
session_start();
include("connection.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Smile 32</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

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

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body >
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-5 py-3 py-lg-0 ">
        <a href="admin_mainpage.php" class="navbar-brand p-0">
            <h1 class="m-0 text-primary"><i class="fa fa-tooth me-2"></i>Smile <span style="color:orange;">32</span></h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="admin_mainpage.php" class="nav-item nav-link active" data-section="content-dashboard">Dashboard</a>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Doctors</a>
                    <div class="dropdown-menu m-0">
                        <a href="add_doctors.php" class="dropdown-item">Add Doctors</a>
                        <a href="doctors_list.php" class="dropdown-item">List Doctors</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Patients</a>
                    <div class="dropdown-menu m-0">
                        <!-- Set the value of $patient in the href attribute -->
                        <a href="add_patient.php" class="dropdown-item">Add Patients</a>
                        <a href="patients_list.php" class="dropdown-item">List patients</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                    <div class="dropdown-menu m-0">
                        <!-- Set the value of $patient in the href attribute -->
                        <a href="services.php" class="dropdown-item">Add Services</a>
                        <a href="services_list.php" class="dropdown-item">Services Price List  </a>
                    </div>
                </div>

                <!--<a href="services.html" class="nav-item nav-link" data-section="content-patient">Services</a>-->
                <a href="appointments.php?display=1" class="nav-item nav-link" data-section="content-appointment">Appointments</a>
              <a href="questions.php" class="nav-item nav-link" data-section="content-services">Questions</a>
              <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Questions</a>
                    <div class="dropdown-menu m-0">
                        <!-- Set the value of $patient in the href attribute -->
                        <a href="user-questions.php" class="dropdown-item">User Questions</a>
                        <a href="questions.php" class="dropdown-item">Pre Build Questions</a>
                    </div>
                </div>
                
            </div>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                 <img src="img/person.png" alt="icon" class="icon">
                </a>
                <div class="dropdown-menu m-0">
                    <a href="#" class="dropdown-item"><?php echo $_SESSION['name'] ?></a>
                    <a href="signup.php" class="dropdown-item">Sign Out</a>
                </div>
            </div>
            <a href="index.html" class="btn btn-primary py-2 px-4 ms-3">User page</a>
        </div>
    </nav>
    
    <!-- Content Divs -->


    </div>

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
        <script src="js/main.js"></script>
        <!-- Template Javascript -->


</body>

</html>