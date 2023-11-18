<?php

include('connection.php');

// Get today's date in the format "YYYY-MM-DD"
$currentDate = date("Y-m-d");

// SQL query to count today's appointments
$sql = "SELECT COUNT(*) AS appointment_count FROM tbl_appointments WHERE created_at = '$currentDate'";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $appointmentCount = $row['appointment_count'];

    if ($appointmentCount > 0) {
    } else {
        $appointmentCount = "<p style='width: 95vh;font-size: small;margin-left: -78px;margin-top: 14px;'>No appointments for today.</p>";
    }
} else {
    echo "Error executing the query: " . $conn->error;
}
//active doctors
$st = 'Active';
$d_sql = "SELECT COUNT(*) AS doctor_count FROM tbl_doctors WHERE status = '$st'";
$result2 = $conn->query($d_sql);

if ($result2) {
    $row = $result2->fetch_assoc();
    $doctorCount = $row['doctor_count'];

    if ($doctorCount > 0) {
    } else {
        $doctorCount = "<p style='width: 95vh;font-size: small;margin-left: -78px;margin-top: 14px;'>No doctor for today.</p>";
    }
} else {
    echo "Error executing the query: " . $conn->error;
}
//active doctors
$st = 'Active';
$p_sql = "SELECT COUNT(*) AS patient_count FROM tbl_patient ";
$result3 = $conn->query($p_sql);

if ($result3) {
    $row = $result3->fetch_assoc();
    $patientCount = $row['patient_count'];

    if ($patientCount > 0) {
    } else {
        $patientCount = "No patients for today.";
    }
} else {
    echo "Error executing the query: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Smile 32</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Dental Clinic" name="keywords">
    <meta content="Dental Clinic" name="description">

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
    <style>
        .main-dashboard {
            width: 100%;
            height: 100%;
            background-color: blue;
        }
    </style>
</head>

<body style="background-color:rgb(82, 202, 230);">
    <?php include('admin_menu.php'); ?>
    <div class="main-dashboard">
        <marquee behavior="alternate" direction="right" scrollamount="6">
            <h2 class="text-uppercase">Welcome to admin Dashboard</h2>
        </marquee>
    </div>
    <div class="count">
        <div class="container">
            <div class="row" style="margin-top: 20px;">
                <div class="col d-flex justify-content-center" style="background-color: #006ccb; margin: 10px;">
                    <a href="appointments.php">
                        <div class="appo" style="margin-top: 22px;">
                            <h3 class="text-uppercase">today Appointments</h3>
                            <div style="color: #fff; font-size: xx-large; display: block; position: absolute; margin-top: 28px; margin-left: 142px;">
                                <?php echo $appointmentCount; ?>
                            </div>
                            <img src="img/schedule.png" alt="" style="width: 215px; height: 215px; margin-left: 39px; margin-top: -13px;">
                        </div>
                    </a>
                </div>

                <div class="col" style="background-color: #ad25a4; margin: 10px;">
                    <a href="patients_list.php">
                        <div class="appo" style="margin-top: 22px;">
                            <center>
                                <h3 class="text-uppercase">number of patients</h3>
                            </center>
                            <div style="color: #fff;font-size: xx-large;display: block;position: absolute;margin-top: 82px;margin-left: 93px;">
                                <?php echo $patientCount; ?>
                            </div>
                            <img src="img/patient.png" alt="" style="width: 216px;height: 186px;margin-left: 63px;margin-top: 9px;">
                        </div>
                    </a>
                </div>
                <div class="col" style="background-color: #006ccb; margin: 10px;">
                    <a href="doctors_list.php">
                        <div class="appo" style="margin-top: 22px;">
                            <center>
                                <h3 class="text-uppercase">number of doctors</h3>
                            </center>
                            <div style="color: #fff;font-size: xx-large;display: block;position: absolute;margin-top: 12px;margin-left: 155px;">
                                <?php echo $doctorCount; ?>
                            </div>
                            <img src="img/dentist.png" alt="" style="width: 216px;height: 186px;margin-left: 63px;margin-top: 9px;">
                        </div>
                    </a>
                </div>

            </div>
            <div class="row">
                <div class="col" style="background-color: #006ccb; margin: 10px;">
                    <a href="user-questions.php">
                        <div class="appo" style="margin-top: 22px;">
                            <center>
                                <h3 class="text-uppercase">Users questions</h3>
                            </center>
                            
                            <center>
                                <img src="img/problem.png" alt="" style="width: 216px;height: 186px;margin-left: 63px;margin-top: 9px;">
                            </center>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <div>
        <h2 style="display: flex;justify-content: center;">Clinic Growth on Pie Chart</h2>
    </div>
    <div style="width: 86%;margin: 10px;margin-left: 99px;display: flex;justify-content: center;">

        <?php include('pieChart.php'); ?>

    </div>
</body>

</html>