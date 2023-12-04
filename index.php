<?php
session_start();
include("connection.php");
global $count;
$count = 0;
function fetchTableData($conn, $tableName)
{
  $sql = "SELECT * FROM $tableName WHERE status='Active'";
  $result = $conn->query($sql);
  $data = [];

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }

  return $data;
}
$feedbacks = fetchTableData($conn, "tbl_feedbacks");

function fetchData($conn, $tableName)
{

  $sql = "SELECT * FROM $tableName ";
  $result = $conn->query($sql);
  $data = [];

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }

  return $data;
}

$questions = fetchData($conn, 'tbl_prebuild_questions');
if (isset($_POST['add_question'])) {
  $question = $_POST['question'];
  if(isset($_SESSION['id'])){
  $userid = $_SESSION['id'];
  $sql = "insert into tbl_questions (user_id,question) values ('$userid','$question')";
  $result = $conn->query($sql);
  header('location:index.php');
  }else{
    echo '<script>alert("Signup / Signin to ask questions");</script>';
  }
  
  
}
$services = fetchTableData($conn, "tbl_services");
$doctors = fetchTableData($conn, "tbl_doctors");
function fetchServiceTableData($conn, $id)
{
  $sql = "SELECT * FROM tbl_price_packages WHERE service_id='$id' AND  status='Active'";
  $result = $conn->query($sql);
  $data = [];

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }

  return $data;
}
if (isset($_SESSION['id'])) {
  $userid = $_SESSION['id'];
  $sql = "SELECT * FROM tbl_questions WHERE user_id='$userid'";
  $result = $conn->query($sql);
  $replys = [];
  $count = 0;
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $count++;
      $replys[] = $row;
    }
  }
}
function name($conn,$pat_id){
  $sql = "SELECT * FROM tbl_patient WHERE patient_id='$pat_id'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $patient_name= $row['full_name'];
    }
  }
  return $patient_name;
}
if (isset($_POST['add_feedback'])) {
  if (isset($_SESSION['id'])) {
    $feedback = $_POST['feedback'];
    $sqlcheck = "SELECT * FROM tbl_appointments WHERE user_id='$userid'";
    $result = $conn->query($sqlcheck);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $patient_id = $row['patient_id'];
      }
      $patient_name=name($conn,$patient_id);

      $sql = "INSERT INTO tbl_feedbacks (patient_name,feedback,status) VALUES('$patient_name','$feedback','Active')";
      $result = $conn->query($sql);
      header('location: index.php');
    } else {
      echo '<script>alert("Please choose a service first ");</script>';
    }
  }else{
    echo '<script>alert("You are not login yet");</script>';
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
  <style>
    #chatbot-icon {
      position: fixed;
      bottom: 430px;
      right: 20px;
      cursor: pointer;
      z-index: 999;
    }

    #chatbot-box {
      position: fixed;
      bottom: 100px;
      color: beige;
      right: -394px;
      /* Initially hidden off-screen */
      width: 344px;
      height: 400px;
      background-color: #2196F3;
      border: 1px solid #ccc;
      transition: right 0.3s ease-in-out;
      z-index: 998;
      box-sizing: border-box;
      overflow-y: auto;
      /* Enable vertical scrolling */
    }

    #close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
    }

    #user-input {
      width: calc(100% - 20px);
      padding: 10px;
      margin: 0 10px 10px;
      box-sizing: border-box;
    }

    .answer {
      display: none;
      margin: 10px;
    }

    @keyframes headShake {

      0%,
      100% {
        transform: translateX(0);
      }

      10%,
      30%,
      50%,
      70%,
      90% {
        transform: translateX(-10px);
      }

      20%,
      40%,
      60%,
      80% {
        transform: translateX(10px);
      }
    }

    #chatbot-icon img {
      animation: headShake 5s ease infinite;
    }

    .notification {
      background: #ff8401;
      font-size: smaller;
      color: #2b2929;
      width: 22px;
      height: 22px;
      margin-top: -13px;
      text-align: center;
      border-radius: 90%;
      padding: 5px 8px;
      position: absolute;
      top: 0;
      right: 0;
    }
  </style>
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
          <small class="py-2"><i class="far fa-clock text-primary me-2"></i>Opening Hours: Mon
            - Tues : 6.00 am - 10.00 pm, Sunday Closed
          </small>
        </div>
      </div>
      <div class="col-md-6 text-center text-lg-end">
        <div class="position-relative d-inline-flex align-items-center text-white top-shape px-5" style="background-color: #06a3da !important">
          <div class="me-3 pe-3 border-end py-2">
            <p class="m-0">
              <i class="fa fa-envelope-open me-2"></i>info@example.com
            </p>
          </div>
          <div class="py-2">
            <p class="m-0">
              <i class="fa fa-phone-alt me-2"></i>+0484 277 7924
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Topbar End -->
  <!-- ChatBot-->
  <div id="chatbot-icon" onclick="toggleChatbox()">

    <img src="img/chatbot.png" alt="Chatbot Icon" width="50">
    <span class="notification"><?php echo $count; ?></span>
  </div>


  <div id="chatbot-box">
    <div id="close-btn" onclick="toggleChatbox2()">X</div>
    <p>Hello! I'm a Enamal.</p>
    <?php foreach ($questions as $index => $question) : ?>



      <button style=" margin-top: 12px;" onclick="showAnswer('<?php echo htmlspecialchars($question['prequestion_id'], ENT_QUOTES, 'UTF-8'); ?>')">
        <?php echo $question['question']; ?>
      </button>
      <div id="answer_<?php echo $question['prequestion_id']; ?>" class="answer">
        <?php echo "<p>" . $question['answer'] . "</p>"; ?>
      </div>

    <?php endforeach; ?>
    <div>
      <?php
      if (!empty($replys)) {
        foreach ($replys as $index => $reply) : ?>
          <p><?php echo 'Question : ' . $reply['question']; ?></p>
          <p><?php echo 'Response : ' . $reply['reply']; ?></p>
      <?php endforeach;
      }
      ?>
    </div>

    <!--Input box for user questions -->
    <div class="row">
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="text" id="user-input" name="question" placeholder="Type your question...">
        <div class="text-center">
          <input type="submit" value="Submit" class="btn btn-success" name="add_question">
        </div>
      </form>
    </div>
  </div>
  <!-- ChatBot End-->

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

  <!-- Full Screen Search Start -->
  <div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content" style="background: rgba(9, 30, 62, 0.7)">
        <div class="modal-header border-0">
          <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body d-flex align-items-center justify-content-center">
          <div class="input-group" style="max-width: 600px">
            <input type="text" class="form-control bg-transparent border-primary p-3" placeholder="Type search keyword" />
            <button class="btn btn-primary px-4">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Full Screen Search End -->

  <!-- Carousel Start -->
  <div class="container-fluid p-0">
    <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="w-100" src="img/carousel-1.jpg" alt="Image" />
          <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
            <div class="p-3" style="max-width: 900px">
              <h5 class="text-white text-uppercase mb-3 animated slideInDown">
                Keep Your Teeth Healthy
              </h5>
              <h1 class="display-1 text-white mb-md-4 animated zoomIn">
                Take The Best Quality Dental Treatment
              </h1>
              <?php if ((isset($_SESSION['user'])) && ($_SESSION['user'] == 'd')) { ?>
                <a href="doctor-patients.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">patients</a>
              <?php } else { ?>
                <a href="user-appointment.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Appointment</a>

                <a href="#about" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Contact Us</a>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <img class="w-100" src="img/carousel-2.jpg" alt="Image" />
          <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
            <div class="p-3" style="max-width: 900px">
              <h5 class="text-white text-uppercase mb-3 animated slideInDown">
                Keep Your Teeth Healthy
              </h5>
              <h1 class="display-1 text-white mb-md-4 animated zoomIn">
                Take The Best Quality Dental Treatment
              </h1>
              <?php if ((isset($_SESSION['user'])) && ($_SESSION['user'] == 'd')) { ?>
                <a href="doctor-patients.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">patients</a>
              <?php } else { ?>
                <a href="user-appointment.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Appointment</a>

                <a href="#about" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Contact Us</a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
  <!-- Carousel End -->

  <!-- Banner Start -->
  <div class="container-fluid banner mb-5">
    <div class="container"></div>
  </div>
  <!-- Banner Start -->

  <!-- About Start -->
  <div class="container-fluid py-5 wow fadeInUp" id="about" data-wow-delay="0.1s">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title mb-4">
            <h5 class="position-relative d-inline-block text-primary text-uppercase">
              About Us
            </h5>
            <h1 class="display-5 mb-0">
              The World's Best Dental Clinic That You Can Trust
            </h1>
          </div>
          <h4 class="text-body fst-italic mb-4">

          </h4>
          <p class="mb-4">

          </p>
          <div class="row g-3">
            <div class="col-sm-6 wow zoomIn" data-wow-delay="0.3s">
              <h5 class="mb-3">
                <i class="fa fa-check-circle text-primary me-3"></i>Goverment Certificated
              </h5>
              <h5 class="mb-3">
                <i class="fa fa-check-circle text-primary me-3"></i>Professional Staff
              </h5>
            </div>
            <div class="col-sm-6 wow zoomIn" data-wow-delay="0.6s">
              <h5 class="mb-3">
                <i class="fa fa-check-circle text-primary me-3"></i>9:00AM-9:00PM
                Opened
              </h5>
              <h5 class="mb-3">
                <i class="fa fa-check-circle text-primary me-3"></i>Fair
                Prices
              </h5>
            </div>
          </div>
          <a href="user-appointment.php" class="btn btn-primary py-3 px-5 mt-4 wow zoomIn" data-wow-delay="0.6s">Make Appointment</a>
        </div>
        <div class="col-lg-5" style="min-height: 500px">
          <div class="position-relative h-100">
            <img class="position-absolute w-100 h-100 rounded wow zoomIn" data-wow-delay="0.9s" src="img/about.jpg" style="object-fit: cover" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- About End -->



  <!-- Service Start -->
  <div class="container-fluid py-5 wow fadeInUp" id="services" data-wow-delay="0.1s">
    <div class="container">
      <div class="row g-5 mb-5">
        <div class="col-lg-5 wow zoomIn" data-wow-delay="0.3s" style="min-height: 400px">
          <!--<div class="twentytwenty-container position-relative h-100 rounded overflow-hidden">-->
          <div class=" position-relative h-100 rounded overflow-hidden">

            <div class="section-title mb-5">
              <h5 class="position-relative d-inline-block text-primary text-uppercase">
                Our Services
              </h5>
              <h1 class="display-5 mb-0">
                We Offer The Best Quality Dental Services
              </h1>
              <div class="service-item wow zoomIn" style="margin-top: 42px;" data-wow-delay="0.9s">
                <a href="user-services.php">
                  <div class="position-relative bg-primary rounded h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                    <h3 class="text-white mb-3">Make Appointment</h3>
                    <p class="text-white mb-3">

                    </p>
                    <h2 class="text-white mb-0">+012 345 6789</h2>
                  </div>
                </a>
              </div>
            </div>
            <!--<img
                class="position-absolute w-100 h-100"
                src="img/before.jpg"
                style="object-fit: cover"
              />
              <img
                class="position-absolute w-100 h-100"
                src="img/after.jpg"
                style="object-fit: cover"
              />-->
          </div>
        </div>
        <!--<div class="col-lg-7">
            
            <div class="row g-5">
              <div
                class="col-md-6 service-item wow zoomIn"
                data-wow-delay="0.6s"
              >
                <a href="user-appointment.php">
                  <div class="rounded-top overflow-hidden">
                    <img class="img-fluid" src="img/service-1.jpg" alt="" />
                  </div>
                  <div
                    class="position-relative bg-light rounded-bottom text-center p-4"
                  >
                    <h5 class="m-0">Cosmetic Dentistry</h5>
                  </div>
                </a>
              </div>
              <div
                class="col-md-6 service-item wow zoomIn"
                data-wow-delay="0.9s"
              >
                <a href="user-appointment.php">
                  <div class="rounded-top overflow-hidden">
                    <img class="img-fluid" src="img/service-2.jpg" alt="" />
                  </div>
                  <div
                    class="position-relative bg-light rounded-bottom text-center p-4"
                  >
                    <h5 class="m-0">Dental Implants</h5>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="row g-5 wow fadeInUp" data-wow-delay="0.1s">
          <div class="col-lg-7">
            <div class="row g-5">
              <div
                class="col-md-6 service-item wow zoomIn"
                data-wow-delay="0.3s"
              >
                <a href="user-appointment.php">
                  <div class="rounded-top overflow-hidden">
                    <img class="img-fluid" src="img/service-3.jpg" alt="" />
                  </div>
                  <div
                    class="position-relative bg-light rounded-bottom text-center p-4"
                  >
                    <h5 class="m-0">Dental Bridges</h5>
                  </div>
                </a>
              </div>
              <div
                class="col-md-6 service-item wow zoomIn"
                data-wow-delay="0.6s"
              >
                <a href="user-appointment.php">
                  <div class="rounded-top overflow-hidden">
                    <img class="img-fluid" src="img/service-4.jpg" alt="" />
                  </div>
                  <div
                    class="position-relative bg-light rounded-bottom text-center p-4"
                  >
                    <h5 class="m-0">Teeth Whitening</h5>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-5 service-item wow zoomIn" data-wow-delay="0.9s">
            <a href="user-appointment.php">
              <div
                class="position-relative bg-primary rounded h-100 d-flex flex-column align-items-center justify-content-center text-center p-4"
              >
                <h3 class="text-white mb-3">Make Appointment</h3>
                <p class="text-white mb-3">
                  Clita ipsum magna kasd rebum at ipsum amet dolor justo dolor
                  est magna stet eirmod
                </p>
                <h2 class="text-white mb-0">+012 345 6789</h2>
              </div>
            </a>
          </div>
        </div>-->
        <div class="col-lg-7">
          <div class="row g-5">
            <?php foreach ($services as $index => $service) : ?>
              <div class="col-md-6 service-item wow zoomIn" data-wow-delay="<?php echo $service['service_name']; ?>">
                <a href="user-services.php#<?php echo $service['service_name']; ?>">
                  <div class="rounded-top overflow-hidden">
                    <img class="img-fluid" style="width: 291px;height: 174px;" src="<?php echo $service['service_image']; ?>" alt="" />
                  </div>
                  <div class="position-relative bg-light rounded-bottom text-center p-4">
                    <h5 class="m-0"><?php echo $service['service_name']; ?></h5>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- <div class="row g-5 wow fadeInUp" data-wow-delay="0.1s">
          <div class="col-lg-7">
             Add code for the second row of service items here if needed 
          </div>
          <div class="col-lg-5 service-item wow zoomIn" data-wow-delay="0.9s">
            <a href="">
              <div class="position-relative bg-primary rounded h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                <h3 class="text-white mb-3">Make Appointment</h3>
                <p class="text-white mb-3">
                Clita ipsum magna kasd rebum at ipsum amet dolor justo dolor
                  est magna stet eirmod
                </p>
                <h2 class="text-white mb-0">+012 345 6789</h2>
              </div>
            </a>
          </div>
        </div>-->
      </div>
    </div>
    <!-- Service End -->

    <!-- Offer Start -->
    <div class="container-fluid bg-offer my-5 py-5 wow fadeInUp" data-wow-delay="0.1s">
      <div class="container py-5">
        <div class="row justify-content-center">
          <div class="col-lg-7 wow zoomIn" data-wow-delay="0.6s">
            <div class="offer-text text-center rounded p-5">
              <h1 class="display-5 text-white">
                We Offer The Best Quality Dental Services
              </h1>
              <p class="text-white mb-4">

              </p>
              <a href="user-appointment.php" class="btn btn-dark py-3 px-5 me-3">Appointment</a>
              <a href="" class="btn btn-light py-3 px-5">Read More</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Offer End -->

    <!-- Pricing Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
      <div class="container">
        <div class="row g-5">
          <div class="col-lg-5">
            <div class="section-title mb-4">
              <h5 class="position-relative d-inline-block text-primary text-uppercase">
                Pricing Plan
              </h5>
              <h1 class="display-5 mb-0">
                We Offer Fair Prices for Dental Treatment
              </h1>
            </div>
            <p class="mb-4">

            </p>
            <h5 class="text-uppercase text-primary wow fadeInUp" data-wow-delay="0.3s">
              Call for Appointment
            </h5>
            <h1 class="wow fadeInUp" data-wow-delay="0.6s">+91 6282 892 170</h1>
          </div>
          <div class="col-lg-7">
            <div class="owl-carousel price-carousel wow zoomIn" data-wow-delay="0.9s">
              <?php foreach ($services as $index => $service) :
                $serviceid = $service['service_id'];
                $price_packages = fetchServiceTableData($conn, $serviceid);
                foreach ($price_packages as $index => $price_package) {
              ?>
                  <div class="price-item pb-4">
                    <div class="position-relative">
                      <img class="img-fluid rounded-top" src="<?php echo $service['service_image'] ?>" alt="" />
                      <div class="d-flex align-items-center justify-content-center bg-light rounded pt-2 px-3 position-absolute top-100 start-50 translate-middle" style="z-index: 2">
                        <h2 class="text-primary m-0">₹<?php echo $price_package['price']; ?></h2>
                      </div>
                    </div>
                    <div class="position-relative text-center bg-light border-bottom border-primary py-5 p-4">
                      <h4><?php echo $price_package['package_name']; ?></h4>
                      <hr class="text-primary w-50 mx-auto mt-0" />
                      <div class="d-flex justify-content-between mb-3">
                        <span>Modern Equipment</span><i class="fa fa-check text-primary pt-1"></i>
                      </div>
                      <div class="d-flex justify-content-between mb-3">
                        <span>Professional Dentist</span><i class="fa fa-check text-primary pt-1"></i>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                        <span> Call Support</span><i class="fa fa-check text-primary pt-1"></i>
                      </div>
                      <a href="user-appointment.php" class="btn btn-primary py-2 px-4 position-absolute top-100 start-50 translate-middle">Appointment</a>
                    </div>
                  </div>
              <?php }
              endforeach; ?>

              <!--<div class="price-item pb-4">
                <div class="position-relative">
                  <img class="img-fluid rounded-top" src="img/price-2.jpg" alt="" />
                  <div class="d-flex align-items-center justify-content-center bg-light rounded pt-2 px-3 position-absolute top-100 start-50 translate-middle" style="z-index: 2">
                    <h2 class="text-primary m-0">₹25000</h2>
                  </div>
                </div>
                <div class="position-relative text-center bg-light border-bottom border-primary py-5 p-4">
                  <h4>Dental Implant</h4>
                  <hr class="text-primary w-50 mx-auto mt-0" />
                  <div class="d-flex justify-content-between mb-3">
                    <span>Modern Equipment</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <div class="d-flex justify-content-between mb-3">
                    <span>Professional Dentist</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <span>24/7 Call Support</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <a href="user-appointment.php" class="btn btn-primary py-2 px-4 position-absolute top-100 start-50 translate-middle">Appointment</a>
                </div>
              </div>
              <div class="price-item pb-4">
                <div class="position-relative">
                  <img class="img-fluid rounded-top" src="img/price-3.jpg" alt="" />
                  <div class="d-flex align-items-center justify-content-center bg-light rounded pt-2 px-3 position-absolute top-100 start-50 translate-middle" style="z-index: 2">
                    <h2 class="text-primary m-0">₹3000</h2>
                  </div>
                </div>
                <div class="position-relative text-center bg-light border-bottom border-primary py-5 p-4">
                  <h4>Dental Bridge</h4>
                  <hr class="text-primary w-50 mx-auto mt-0" />
                  <div class="d-flex justify-content-between mb-3">
                    <span>Modern Equipment</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <div class="d-flex justify-content-between mb-3">
                    <span>Professional Dentist</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <span>24/7 Call Support</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <a href="user-appointment.php" class="btn btn-primary py-2 px-4 position-absolute top-100 start-50 translate-middle">Appointment</a>
                </div>
               
              </div>
              <div class="price-item pb-4">
                <div class="position-relative">
                  <img class="img-fluid rounded-top" src="img/price-3.jpg" alt="" />
                  <div class="d-flex align-items-center justify-content-center bg-light rounded pt-2 px-3 position-absolute top-100 start-50 translate-middle" style="z-index: 2">
                    <h2 class="text-primary m-0">₹2000</h2>
                  </div>
                </div>
                <div class="position-relative text-center bg-light border-bottom border-primary py-5 p-4">
                  <h4>Cosmetic Dentistry</h4>
                  <hr class="text-primary w-50 mx-auto mt-0" />
                  <div class="d-flex justify-content-between mb-3">
                    <span>Modern Equipment</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <div class="d-flex justify-content-between mb-3">
                    <span>Professional Dentist</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <span>24/7 Call Support</span><i class="fa fa-check text-primary pt-1"></i>
                  </div>
                  <a href="user-appointment.php" class="btn btn-primary py-2 px-4 position-absolute top-100 start-50 translate-middle">Appointment</a>
                </div>
               
              </div>-->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Pricing End -->

    <!-- Testimonial Start -->
    <div class="container-fluid bg-primary bg-testimonial py-5 my-5 wow fadeInUp" data-wow-delay="0.1s">
      <div class="container py-5">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="owl-carousel testimonial-carousel rounded p-5 wow zoomIn" data-wow-delay="0.6s">
              <?php foreach ($feedbacks as $index => $feedback) : ?>
                <div class="testimonial-item text-center text-white">
                  <!--<img class="img-fluid mx-auto rounded mb-4" src="img/testimonial-1.jpg" alt="" />-->
                  <p class="fs-5">"
                  <?= $feedback['feedback'];?>"
                  </p>
                  <hr class="mx-auto w-25" />
                  <h4 class="text-white mb-0"><?= $feedback['patient_name'];?></h4>
                </div>
              <?php endforeach; ?>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Testimonial End -->
    <!-- Appointment Start -->
    <div class="container-fluid bg-primary bg-appointment my-5 wow fadeInUp" data-wow-delay="0.1s">
      <div class="container">
        <div class="row gx-5">
          <div class="col-lg-6 py-5">
            <div class="py-5">
              <h1 class="display-5 text-white mb-4">Your smile is our priority! </h1>
              <p class="text-white mb-0">Share your experience and help us continue providing exceptional dental care. Your feedback is valuable in our mission to create healthier, happier smiles.Response to your Feedbacks will be sent via email. Share your thoughts today!</p>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="appointment-form h-100 d-flex flex-column justify-content-center text-center p-5 wow zoomIn" data-wow-delay="0.6s">
              <h1 class="text-white mb-4">Share Your feedbacks</h1>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="row g-3">

                  <div class="col-12 col-sm-12">
                    <textarea type="text" class="form-control bg-light border-0" placeholder="Your Name" name="feedback" style="height: 55px;"></textarea>
                  </div>
                  <div class="col-12">
                    <input class="btn btn-dark w-100 py-3" type="submit" name="add_feedback" value="Share With Us">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Appointment End -->

    <!-- Team Start -->
    <div class="container-fluid py-5" id="dentist" style="background-color: #f57e57">
      <div class="container">
        <div class="row g-5">
          <div class="col-lg-4 wow slideInUp" data-wow-delay="0.1s">
            <div class="section-title bg-light rounded h-100 p-5">
              <h5 class="position-relative d-inline-block text-primary text-uppercase">
                Our Dentist
              </h5>
              <h1 class="display-6 mb-4">
                Meet Our Certified & Experienced Dentist
              </h1>
              <a href="user-appointment.php" class="btn btn-primary py-3 px-5">Appointment</a>
            </div>
          </div>
          <?php foreach ($doctors as $index => $doctor) : ?>
            <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
              <div class="team-item">
                <div class="position-relative rounded-top" style="z-index: 1">
                  <img class="img-fluid rounded-top w-100" src="<?php echo $doctor['doctor_image']; ?>" alt="not availabe " />
                  <div class="position-absolute top-100 start-50 translate-middle bg-light rounded p-2 d-flex">
                    <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                    <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                    <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                    <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                  </div>
                </div>
                <div class="team-text position-relative bg-light text-center rounded-bottom p-4 pt-5">
                  <h4 class="mb-2">Dr. <?php echo $doctor['doctor_name']; ?> </h4>
                  <p class="text-primary mb-0">Qualification :<?php echo $doctor['qualification']; ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <!--<div class="col-lg-4 wow slideInUp" data-wow-delay="0.6s">
            <div class="team-item">
              <div class="position-relative rounded-top" style="z-index: 1">
                <img class="img-fluid rounded-top w-100" src="img/team-2.jpg" alt="" />
                <div class="position-absolute top-100 start-50 translate-middle bg-light rounded p-2 d-flex">
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                </div>
              </div>
              <div class="team-text position-relative bg-light text-center rounded-bottom p-4 pt-5">
                <h4 class="mb-2">Dr. Sabu</h4>
                <p class="text-primary mb-0">Implant Surgeon</p>
              </div>
            </div>
          </div>-->
          <!--<div class="col-lg-4 wow slideInUp" data-wow-delay="0.1s">
            <div class="team-item">
              <div class="position-relative rounded-top" style="z-index: 1">
                <img class="img-fluid rounded-top w-100" src="img/team-3.jpg" alt="" />
                <div class="position-absolute top-100 start-50 translate-middle bg-light rounded p-2 d-flex">
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                </div>
              </div>
              <div class="team-text position-relative bg-light text-center rounded-bottom p-4 pt-5">
                <h4 class="mb-2">Dr. John Doe</h4>
                <p class="text-primary mb-0">Implant Surgeon</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
            <div class="team-item">
              <div class="position-relative rounded-top" style="z-index: 1">
                <img class="img-fluid rounded-top w-100" src="img/team-4.jpg" alt="" />
                <div class="position-absolute top-100 start-50 translate-middle bg-light rounded p-2 d-flex">
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                </div>
              </div>
              <div class="team-text position-relative bg-light text-center rounded-bottom p-4 pt-5">
                <h4 class="mb-2">Dr. John Doe</h4>
                <p class="text-primary mb-0">Implant Surgeon</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 wow slideInUp" data-wow-delay="0.6s">
            <div class="team-item">
              <div class="position-relative rounded-top" style="z-index: 1">
                <img class="img-fluid rounded-top w-100" src="img/team-5.jpg" alt="" />
                <div class="position-absolute top-100 start-50 translate-middle bg-light rounded p-2 d-flex">
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                  <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                </div>
              </div>
              <div class="team-text position-relative bg-light text-center rounded-bottom p-4 pt-5">
                <h4 class="mb-2">Dr. John Doe</h4>
                <p class="text-primary mb-0">Implant Surgeon</p>
              </div>
            </div>
          </div>-->
        </div>
      </div>
    </div>
    <!-- Team End -->

    <!-- Newsletter Start -->
    <div class="container-fluid position-relative pt-5 wow fadeInUp" data-wow-delay="0.1s" style="z-index: 1">
      <div class="container">
        <div class="bg-primary p-5">
          <form class="mx-auto" style="max-width: 600px">
            <!-- <div class="input-group">
              <input type="text" class="form-control border-white p-3" placeholder="Your Email" />
              <button class="btn btn-dark px-4">Sign Up</button>
            </div>-->
          </form>
        </div>
      </div>
    </div>
    <!-- Newsletter End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light py-5 wow fadeInUp" data-wow-delay="0.3s" style="margin-top: -75px">
      <div class="container pt-5">
        <div class="row g-5 pt-4">
          <div class="col-lg-3 col-md-6">
            <h3 class="text-white mb-4">Quick Links</h3>
            <div class="d-flex flex-column justify-content-start">
              <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
              <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
              <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our
                Services</a>
              <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest
                Blog</a>
              <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact
                Us</a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <h3 class="text-white mb-4">Popular Links</h3>
            <div class="d-flex flex-column justify-content-start">
              <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
              <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
              <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our
                Services</a>
              <a class="text-light mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest
                Blog</a>
              <a class="text-light" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact
                Us</a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <h3 class="text-white mb-4">Get In Touch</h3>
            <p class="mb-2">
              <i class="bi bi-geo-alt text-primary me-2"></i>Tripunithura. 3.0. Layam Road, Tripunithura, Ernakulam - 682301 (Near Chakkankulangara Temple).

            </p>
            <p class="mb-2">
              <i class="bi bi-envelope-open text-primary me-2"></i>dental@gmail.com
            </p>
            <p class="mb-0">
              <i class="bi bi-telephone text-primary me-2"></i>+012 345 67890
            </p>
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
    <div class="container-fluid text-light py-4" style="background: #051225">
      <div class="container">
        <div class="row g-0">
          <div class="col-md-6 text-center text-md-start">
            <p class="mb-md-0">
              &copy;
              <a class="text-white border-bottom" href="#">Smile32</a>.
              All Rights Reserved.
            </p>
          </div>
          <div class="col-md-6 text-center text-md-end">
            <p class="mb-0">
              Designed by
              <a class="text-white border-bottom" href="https://github.com/AravindhSunilkumar/mini-project">Aravindh Sunilkumar</a>
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script>
      function toggleChatbox() {
        var chatbox = document.getElementById("chatbot-box");
        var chaticon = document.getElementById("chatbot-icon");
        chaticon.style.display = 'none';
        var currentRight = parseInt(window.getComputedStyle(chatbox).right);

        if (currentRight === 0) {
          chatbox.style.right = "-394px";
        } else {
          chatbox.style.right = "0";
        }
      }

      function toggleChatbox2() {
        
        var chatbox = document.getElementById("chatbot-box");
        var chaticon = document.getElementById("chatbot-icon");
        chaticon.style.display = ''; // Corrected from 'display' to 'none'
        var currentRight = parseInt(window.getComputedStyle(chatbox).right);

        if (currentRight === 0) {
          chatbox.style.right = "-394px";
        } else {
          chatbox.style.right = "0";
        }
      }

      function showAnswer(questionId) {
        var answerDiv = document.getElementById("answer_" + questionId);
        // Toggle the display property
        answerDiv.style.display = answerDiv.style.display === 'none' ? 'block' : 'none';
      }
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