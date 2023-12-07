<?php
session_start();
include('connection.php');
$doctor_id = $_SESSION['id'];
$sql = "SELECT * FROM tbl_doctors WHERE doctor_id = '$doctor_id'";
$result = $conn->query($sql);
$doctors = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}
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
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/services.css">
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
                <!-- <a href="index.html" class="nav-item nav-link active">Home</a>
                <a href="#about" class="nav-item nav-link">About Us</a>
                <a href="#services" class="nav-item nav-link">Service</a>
                <a href="#dentist" class="nav-item nav-link">Our Dentist</a>
                <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
          <div class="dropdown-menu m-0">
            <a href="price.php" class="dropdown-item">Pricing Plan</a>
            <a href="team.php" class="dropdown-item">Our Dentist</a>
            <a href="testimonial.php" class="dropdown-item">Testimonial</a>
            <a href="appointment.php" class="dropdown-item">Appointment</a>
          </div>
        </div>-->


        <a href="today-appointment.php" class="nav-item nav-link">Today Appointment</a> 
                <a href="doctor-patients.php?id=<?= 1 ?>" class="nav-item nav-link">Cosmetic Dentistry</a>
                <a href="doctor-patients.php?id=<?= 2 ?>" class="nav-item nav-link">Dental Implant</a>
                <a href="doctor-patients.php?id=<?= 3 ?>" class="nav-item nav-link">Dental Bridges</a>
                <a href="doctor-patients.php?id=<?= 4 ?>" class="nav-item nav-link">Teeth Whitening</a>
                <a href="doctor-profile.php" class="nav-item nav-link">My Profile</a>






            </div>
            <?php if (isset($_SESSION['name'])) { ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><img src="img/person.png" alt="icon" class="icon" /></a>
                    <div class="dropdown-menu m-0">
                    <?php foreach ($doctors as $index => $doctor) : ?> 
                    <a href="javascript:void(0);"  onclick="showEditForm( <?= $doctor['doctor_id']; ?>, '<?= $doctor['doctor_name']; ?>', '<?= $doctor['age']; ?>', '<?= $doctor['gender']; ?>', '<?= $doctor['services']; ?>', '<?= $doctor['qualification']; ?>'
                    )"class="dropdown-item"><?php echo $_SESSION['name'] ?></a>
                    <?php endforeach; ?>
                        <a href="logout.php" class="dropdown-item">SignOut</a>
                    </div>
                </div>
            <?php }  ?>



        </div>
    </nav>
    <!-- Navbar End -->
    <center>
        <div class="w3-third" style="width:30%;" class="justify-content-center">

            <div class="w3-white w3-text-grey w3-card-4 ">
                <?php foreach ($doctors as $index => $doctor) : ?>
       
                    <div class="d-flex justify-content-center w3-display-container">
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                            <label for="fileInput" class="file-label">
                                <?php if (!empty($doctor['doctor_image'])) { ?>
                                    <img src="<?php echo $doctor['doctor_image'] ?>" style="margin-left: 41px; width: 70%" alt="Avatar" class="img-fluid rounded-circle">
                                <?php } else { ?>
                                    <img src="img/person.png" style="margin-left: 41px; width: 70%" alt="Avatar" class="img-fluid rounded-circle">
                                <?php } ?>
                            </label>
                            <input type="file" id="fileInput" name="profile_image" class="file-input" accept="image/*" style="display:none;">
                            <input type="submit" value="Change" name="profile" style="margin-left: px;">
                        </form>



                    </div>



                    <div class="w3-container">
                        <div class="w3-display-bottomleft w3-container w3-text-black">
                            <div>

                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h2 class=" w3-margin-right w3-large "><?php echo $doctor['doctor_name']; ?></h2>
                        </div>
                        <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i>AGE:<?php echo $doctor['age']; ?></p>
                        <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i>Gender:<?php echo $doctor['gender']; ?></p>
                        <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i>Services:<?php echo $doctor['services']; ?></p>
                        <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i>Qualification:<?php echo $doctor['qualification']; ?></p>
                        <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i>Gender:<?php echo $doctor['gender']; ?></p>
                        <p><i class="fa-fw w3-margin-right w3-large w3-text-teal"><img class="icon" src="img/scribble.png" alt=""></i><?php echo $doctor['email']; ?></p>
                        <hr>
                        <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(
        <?= $doctor['doctor_id']; ?>,
        '<?= $doctor['doctor_name']; ?>',
        '<?= $doctor['age']; ?>',
        '<?= $doctor['gender']; ?>',
        '<?= $doctor['services']; ?>',
        '<?= $doctor['qualification']; ?>'
    )">Edit</a>
                    </div>
            </div><br>




        <?php endforeach; ?>
        <!-- End Left Column -->
        </div>

    </center>
    

    <!-- JavaScript to handle edit form display and submission -->
    <!-- ... your existing code ... -->

    <!-- JavaScript to handle edit form display and submission -->
    <!-- Modal for editing doctor details -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Doctor Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <script>
                        function showEditForm(doctorId, doctorName, age, gender, services, qualification) {
                            var modal = document.getElementById("editModal");
                            var modalBody = modal.querySelector(".modal-body");

                            var form = `
            <form action="" method="post">
                <input type="hidden" name="doctor_id" value="${doctorId}">
                <label>Doctor Name:</label>
                <input type="text" name="new_doctor_name" value="${doctorName}" required><br>
                <label>Age:</label>
                <input type="text" name="new_age" value="${age}" required><br>
                <label>Gender:</label>
                <input type="text" name="new_gender" value="${gender}" required><br>
                <label>Services:</label>
                <input type="text" name="new_services" value="${services}" required><br>
                <label>Qualification:</label>
                <input type="text" name="new_qualification" value="${qualification}" required><br>
                <button type="submit" name="update_doctor" class="btn btn-success">Update</button>
                
            </form>
        `;

                            modalBody.innerHTML = form;
                            $(modal).modal("show");
                        }
                    </script>


                </div>
            </div>
        </div>
    </div>
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