<?php
session_start();
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Smile 32</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <!-- Favicon -->
    <link rel="icon" href="./img/tooth.png" type="image/png" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link href="lib/animate/animate.min.css" rel="stylesheet" />
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="lib/twentytwenty/twentytwenty.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="css/add_doctors.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- Navbar Start -->
    <nav class="bg-white navbar navbar-expand-lg navbar-light shadow-sm px-5 py-3 py-lg-0">
        <a href="index.html" class="navbar-brand p-0">
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
                <a href="index.html" class="nav-item nav-link active">Home</a>
                <a href="#about" class="nav-item nav-link">About Us</a>
                <a href="#services" class="nav-item nav-link">Service</a>
                <a href="#dentist" class="nav-item nav-link">Our Dentist</a>
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
                <?php } else { ?>
                    <a href="doctor-patients.php" class="nav-item nav-link">Patients</a>
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
    <div style="display: grid;row-gap: 13px;margin-top: 10px;">
        <div class="d-flex d-name ">
            <div class="d-flex justify-content-center" style="width:100%;height:100%;float:left;">
                <h2 class='text-uppercase'>welcome back dr <?php echo $_SESSION['name'] ?></h2>
            </div>


        </div>
        <div style="width: 100%;display: flex;height: 100%;background-color: #d7cccc;justify-content: center;">
            <table class="col-* table table-success table-striped shadow-lg t-hover" style="width:75%;margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Patient Image</th>                        
                        <th>Patient Name</th>                        
                        <th>Age</th>                        
                        <th>Gender</th>                        
                        <th>Services</th>                        
                        <th>Qualification</th>                        
                        <th>Status</th>                        
                        <th>Doctor Joined Date</th>                        
                        <th>Update</th>                        

                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
        </div>
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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>