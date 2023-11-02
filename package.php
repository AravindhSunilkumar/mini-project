<?php
session_start();
include("connection.php");
$id = $_SESSION['id'];
$serviceId = $_SESSION['s_id'];
function name($conn, $id)
{
    global $data;
    $sqlfetch = "SELECT * FROM tbl_services WHERE service_id = '$id'";
    $result4 = $conn->query($sqlfetch);


    if ($result4->num_rows > 0) {
        while ($row = $result4->fetch_assoc()) {
            $data = $row['service_name'];
        }
    }
    return $data;
}
$service_name=name($conn,$serviceId);
function fetchTableData($conn, $tableName, $serviceId)
{
    $sql = "SELECT * FROM $tableName WHERE service_id = $serviceId";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}
function patientid($conn, $userid)
{
    $sqlfetch = "SELECT patient_id FROM tbl_patient WHERE user_id = '$userid'";
    $result4 = $conn->query($sqlfetch);
    $data = [];

    if ($result4->num_rows > 0) {
        while ($row = $result4->fetch_assoc()) {
            $data = $row['patient_id'];
        }
    }
    return $data;
}
function packprice($conn, $id)
{
    global $data;
    $sqlfetch = "SELECT price FROM tbl_price_packages WHERE package_id = '$id'";
    $result4 = $conn->query($sqlfetch);


    if ($result4->num_rows > 0) {
        while ($row = $result4->fetch_assoc()) {
            $data = $row['price'];
        }
    }
    return $data;
}


$packages = fetchTableData($conn, "tbl_price_packages", $serviceId);

$price = 0; // Initialize $price

if (isset($_POST['choose'])) {
    $packageid = (string)$_POST['package_id'];
   

    $sql = "SELECT * FROM tbl_price_packages WHERE package_id = '$packageid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
       
        $package_name = $row['package_name'];
        $price = $row['price'];
    }

    $patient_id = patientid($conn, $id);
    $pack_price = packprice($conn, $packageid);
    $pack_price = (string)$pack_price; // Ensure $pack_price is a string
    $_SESSION['patient_id'] = $patient_id;
    $_SESSION['pack_price'] = $pack_price;
  
    if ($serviceId == 5) {
        
        
        $currentpayamount = intval($pack_price) / 12;
        //echo $currentpayamount;
    } elseif ($serviceId == 1) {
        $currentpayamount = intval($pack_price) / 6;
    } else {
        $currentpayamount = $pack_price;
    }


    $sqlupdate = "UPDATE tbl_appointments SET package_id = '$packageid', due_amount = '$pack_price' WHERE patient_id = '$patient_id'";
    $result2 = $conn->query($sqlupdate);
    if (!$result2) {
        die("Query error: " . $conn->error);
    }
   
    $flag = 1;
}
if(isset($_POST['paynow'])){
    header('location : payment.php');
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
</head>

<body>
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


    <div style="display: flex;justify-content: center;margin-top:20px;" class="carousel slide carousel-fade">
        <div class="offer-text text-center rounded p-5" style="width: 70%;">
        <h2><?php echo $service_name; ?></h2>
       
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <?php if (isset($flag) && $flag == 1) : ?>
                    
                    <div class="d-flex justify-content-center">
                       
                        <div class="" style="width:40%;font-size: larger;display: grid;font-family: 'Jost';color: #fff;">
                        <h2 style="color:#fff"><center>Choosed package</center></h2>
                            <p>Choosed pack :<?php echo $package_name; ?></p>
                            <p>Total price :<?php echo $price;
                                            if ($packageid == 5 || $packageid == 1) { ?></p><br>
                            <p>Initial Amount : <?php echo $currentpayamount; ?></p>
                        <?php } else { ?>
                            <p>Amount to pay :<?php echo $currentpayamount;
                                            } ?></p>
                            
                           <!-- <input type="submit" class="btn btn-dark py-3 px-5 me-3"  name="paynow"  value="PAY">-->
                           <!--<a href="payment.php?payamount=<?php echo $currentpayamount ?>" class="btn btn-light py-3 px-5">PAY</a>-->
                            <a href="payment.php?payamount=<?php echo $currentpayamount ?>" class="btn btn-dark py-3 px-5 me-3">PAY</a>

                           


                        </div>
                    </div>
                <?php else : ?>
                    <div class="d-flex justify-content-center">
                    <h1> Choose convenient Package </h1>
                        <div class="d-flex" style="width:40%;">
                            <select class="form-select bg-light border-0" name="package_id" style="height:54px;" required>
                                <?php
                                foreach ($packages as $index => $package) :
                                    $package_id = $package["package_id"];
                                    $package_name = $package["package_name"];
                                    echo "<option value=\"$package_id\">$package_name</option>";
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div>
                            <input type="submit" value="Select" name="choose" class="btn btn-dark py-3 px-5 me-3">
                        </div>
                    </div>
                <?php endif; ?>
            </form>



            <p class="text-white mb-4">

            </p>
            
            <!--<a href="" class="btn btn-light py-3 px-5">Read More</a>-->
        </div>
    </div>
    <div style="margin-top:100px;">
    <?php include('footer.php');?>
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