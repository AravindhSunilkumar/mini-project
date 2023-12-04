<?php
session_start();
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
$services = fetchTableData($conn, "tbl_services");
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
                <?php } else { ?>
                    <a href="doctor-patients.php?id=<?= 5 ?>" class="nav-item nav-link">Patients</a>
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
    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 hero-header mb-5">
        <div class="row py-3">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white animated zoomIn">Services</h1>
                <a href="index.php#services" class="h4 text-white">Home</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="user-appointment.php" class="h4 text-white">Services</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->
    <!-- Services -->
    <div class="d-flex" style="width: 100%;height:100%">
        <div class="container" style="margin: 0px;">
            <div id="Cosmetic Dentistry" class="row container-fluid py-5 wow fadeInUp" style="width: 121%;">
                <div class="col" style="margin:10px;height: 50vh;">

                    <iframe width="853" style="margin-top: 0px;width: 100%;height: 99%;" height="480" src="https://www.youtube.com/embed/nznWnGLZffw" title="Cosmetic Dentistry Procedures - Dental Animation" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

                </div>
                <div id="cos" class="col justify-content-center" style="margin:10px;height: 50vh;display:grid;">
                    <h2>Cosmetic dentistry</h2>
                    <p>Cosmetic dentistry is a branch of dentistry that focuses on improving the appearance of your teeth, gums, and/or bite. It can help with:
                        Common cosmetic dental treatments include: Inlays and onlays, Composite bonding, Dental veneers, Teeth whitening, Implants.Cosmetic dentistry can improve dental aesthetics in:
                        Color, Position, Shape, Size, Alignment, Overall smile appearance.
                        Most dental restorations are not permanent. However, some, like porcelain veneers, may last for 10 or more years. Porcelain crowns can last even longer.</p>
                    <center><a href="user-appointment.php" class="btn btn-primary py-3 px-5 mt-4 wow zoomIn" style="width: 217px;height: 69px;" data-wow-delay="0.6s">book Now</a></center>
                </div>

            </div>
            <div class="row container-fluid py-5 wow fadeInUp" style="width: 121%;margin-top: 130px;">

                <div id="Dental Implants" class="col justify-content-center" style="margin:10px;height: 50vh;display:grid;">
                    <h2>Dental Implant</h2>
                    <p>A dental implant is a medical device that is surgically placed in the jaw to replace a missing tooth. The implant serves as the root of the missing tooth and provides support for artificial teeth, such as crowns, bridges, or dentures.
                        Dental implants are permanent metal screws that are inserted into the jawbone.
                        The procedure is performed with either general or local anesthesia to numb the mouth. After the numbness wears off, the patient may experience mild pain. However, people who have undergone the procedure say the pain is less than the pain of tooth extraction.
                        The implant screw itself can last a lifetime with regular brushing and flossing, and regular dental check-ups every 6 months. The crown, however, usually only lasts about 10 to 15 years before it may need a replacement due to wear and tear.</p>
                    <center><a href="user-appointment.php" class="btn btn-primary py-3 px-5 mt-4 wow zoomIn" style="width: 217px;height: 69px;" data-wow-delay="0.6s">book Now</a></center>
                </div>
                <div class="col" style="margin:10px;height: 50vh;">

                    <iframe style="margin-top: 0px;width: 100%;height: 99%;" width="853" height="480" src="https://www.youtube.com/embed/DZ2lDxO4LCc" title="Dental Implant Procedure" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

                </div>

            </div>
            <div class="row container-fluid py-5 wow fadeInUp" style="width: 121%;margin-top: 130px;">
                <div class="col" style="margin:10px;height: 50vh;">

                    <iframe style="margin-top: 0px;width: 100%;height: 99%;" width="853" height="480" src="https://www.youtube.com/embed/gdL5Dy3ml00" title="Conventional Dental Bridge" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

                </div>
                <div id="Dental Bridge" class="col justify-content-center" style="margin:10px;height: 50vh;display:grid;">
                    <h2>Dental Bridge</h2>
                    <p>A dental bridge is a permanent appliance that replaces one or more missing teeth. It consists of:
                        Dental crowns that fit over healthy teeth on either side of the gap
                        Artificial teeth that bridge the gap
                        Dental bridges are used to:
                        Bridge the gap between two healthy teeth where one or more teeth are missing
                        Replace one or more missing or broken teeth with fake teeth
                        Some disadvantages of dental bridges include:
                        Traditional bridges require putting crowns over perfectly healthy teeth
                        Maryland bridges can cause damage to the existing teeth and are not sturdy
                        Implant supported bridges take longer and cost more
                        Bridges don't correct bone loss in the jaw
                        Bridges don't last as long as implants
                        Installing a dental bridge is not painful. However, you may experience some discomfort and sensitivity after the treatment. Preparing your teeth for dental bridges can also be quite painful.</p>
                    <center><a href="user-appointment.php" class="btn btn-primary py-3 px-5 mt-4 wow zoomIn" style="width: 217px;height: 69px;" data-wow-delay="0.6s">book Now</a></center>
                </div>

            </div>
            <div class="row container-fluid py-5 wow fadeInUp" style="width: 121%;margin-top: 130px;">

                <div id="Teeth Whitening" class="col justify-content-center" style="margin:10px;height: 50vh;display:grid;">
                    <h2>Teeth Whitening</h2>
                    <p>Teeth whitening is a process that lightens the natural color of your teeth. It can't make your teeth brilliant white, but it can make them appear brighter and whiter.
                        Teeth whitening works by:
                        Penetrating the enamel and oxidizing dark pigmented molecules within the tooth structure
                        Breaking down darkly colored molecules, which bleaches the underlying tooth color to a whiter shade
                        Teeth whitening can be done in-office or at home. A single session can last anywhere between 40 minutes to an hour. With proper care, the results can last for 1-3 years.
                        Teeth whitening can cause damage to the enamel if not done correctly or overused. People with sensitivity to teeth whitening may experience discomfort or pain during or after treatment.
                        Dental veneers are a permanent way to whiten your teeth. They are thin pieces of porcelain material that are placed on top of natural teeth.
                    </p>
                    <center><a href="user-appointment.php" class="btn btn-primary py-3 px-5 mt-4 wow zoomIn" style="width: 217px;height: 69px;" data-wow-delay="0.6s">book Now</a></center>
                </div>
                <div class="col" style="margin:10px;height: 50vh;">

                    <iframe style="margin-top: 0px;width: 100%;height: 99%;" width="853" height="480" src="https://www.youtube.com/embed/IE1P9O3myWw" title="Teeth Whitening At The Dentist | Fastest Way To Whiten Your Teeth" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

                </div>

            </div>
            <div  id="dental Braces" class="row container-fluid py-5 wow fadeInUp" style="width: 121%;margin-top: 130px;">
                <div class="col" style="margin:10px;height: 50vh;">

                    <iframe style="margin-top: 0px;width: 100%;height: 99%;" width="853" height="480" src="https://www.youtube.com/embed/RU-YnYTd1qk" title="How Does Braces Works" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

                </div>

                <div id="" class="col justify-content-center" style="margin:10px;height: 50vh;display:grid;">
                    <h2>Dental Braces</h2>
                    <p>Dental braces are devices used in orthodontics to align and straighten teeth. They can correct a wide range of dental issues, including: Crooked teeth, Gapped teeth, Rotated teeth, Crowded teeth.
                        Traditional braces involve brackets and wires, usually made out of metal, which are attached to the teeth and allow their positioning to be altered as required.
                        The actual time depends on the patient's specific needs, but many adult patients can look to have braces anywhere from 18 months to about three years.
                        Braces do not hurt at all when they are applied to the teeth, but there will be mild soreness or discomfort after the orthodontic wire is engaged into the newly placed brackets, which may last for a few days to a week.
                        Braces can improve your smile's health, function and appearance. However, they can also result in an increased risk of dental cavities.

                    </p>
                    <center><a href="user-appointment.php" class="btn btn-primary py-3 px-5 mt-4 wow zoomIn" style="width: 217px;height: 69px;" data-wow-delay="0.6s">book Now</a></center>
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