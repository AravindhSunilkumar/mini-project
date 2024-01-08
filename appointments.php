<?php
include("connection.php");
include("message.php");

$A_end_time = $A_start_time = $full_name = $d = $s = $need_date = '';
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

function fetchTableDoctorTimeData($conn, $tableName)
{
  $sql = "SELECT * FROM $tableName WHERE status = 'Active' ";
  $result = $conn->query($sql);
  $data = [];

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }

  return $data;
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

// Example usage:
$services = fetchTableData($conn, "tbl_services");

$patients = fetchTableData($conn, "tbl_patient");
$doctors = fetchTableData($conn, "tbl_doctors");
$doctorTimes = fetchTableDoctorTimeData($conn, "tbl_doctorTime");
// Fetch appointment data
$sql = "SELECT * FROM tbl_appointments";
$result = $conn->query($sql);
$appmts = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $appmts[] = $row;
  }
}
// Handle status update
if (isset($_POST['update_status'])) {
  $appointment_id = $_POST['appointment_id'];
  $status = $_POST['status'];


  $update_sql = "UPDATE tbl_appointments SET 
                 status = '$status'
                 WHERE appointment_id = '$appointment_id'";

  if ($conn->query($update_sql) === TRUE) {
    // Update successful
    // echo "Update successful!";
    //header("Location: doctors_list.php");
    //exit();

    $sql = "SELECT * FROM tbl_appointments WHERE appointment_id = '$appointment_id'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Output data of each row
      while ($row = $result->fetch_assoc()) {
        $email = $row["patient_email"];
        $date = $row['appointmentneed_date'];
        $time = $row['appo_time'];
        $id = $row['service_id'];
        $pid = $row['patient_id'];
        $paids = fetchName($conn, $pid, "patient_id", "tbl_patient");
        foreach ($paids as $index => $paid) :
          $p = $paid['full_name'];

        endforeach;
        $servicess = fetchName($conn, $id, "service_id", "tbl_services");
        foreach ($servicess as $index => $servic) :
          $sn = $servic['service_name'];

        endforeach;
      }
    }
    if ($status == 'rejected') {
      $subject = "Subject: Rejection of Your " . $status . " Appointment Request";
      $body = "Dear " . $p . ",\nWe regret to inform you that your requested appointment for " . $sn . " service has been rejected. We appreciate your understanding and apologize for any inconvenience this may cause.\n\n\n
          Unfortunately, we are unable to accommodate your appointment request at this time. If you have any questions or concerns, please feel free to contact our clinic's reception at 7567467667 or reply to this email.\n\n
          We understand the importance of your dental care, and we hope to have the opportunity to serve you in the future.\n\n
          Thank you for considering Smaile 32 for your dental needs. We appreciate your interest and look forward to the possibility of assisting you in the future.\n\n
          Warm regards,\n\n
          Dental Group\nSmile 32";
    } else {
      $subject = "Subject: Confirmation of Your " . $status . " Appointment Request";
      $body = "Dear " . $p . ",\nI hope this email finds you in good health and high spirits. We are pleased to inform you that your requested appointment for " . $sn . " service has been " . $status . ". Your dental care is of the utmost importance to us, and we are committed to providing you with the best possible treatment.\n\n\n
      Here are the details of your " . $status . " appointment:\n\n 
      
      Date: " . $date . "\n
      Time: " . $time . "\n
      Dental Service: " . $sn . "\n\n
      
      Please note that we will do our best to ensure your visit is as comfortable and convenient as possible. To make your appointment go smoothly, kindly remember to bring any relevant documents or records, and arrive a few minutes early.\n
      
      If you have any questions or need to make any changes to your appointment, please do not hesitate to contact our clinic's reception at 7567467667 or reply to this email.\n
      
      We look forward to seeing you on the scheduled date and time. Your oral health is our priority, and we are here to provide you with the care you deserve.\n
      
      Thank you for choosing Smaile 32 for your dental needs. We value your trust and confidence in our services.\n
      
      Warm regards,\n\n
      
      Dental Group\n
      Smile 32
      
      ";
    }
    email($email, $subject, $body);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  } else {
    // Update failed
    //  echo "Error updating record: " . $conn->error;
  }


  // Redirect back to the doctor list page after updating
  //header("Location: doctors_list.php");
  //exit();
}
// Filter by date
// Reassign an empty array to clear previous data
if (isset($_POST['filter'])) {
  $appmts = array();
  $filter_date = $_POST['filter_date'];

  $sql = "SELECT * FROM tbl_appointments WHERE created_at = '$filter_date'";
  $result = $conn->query($sql);
  if (!$result) {
    die("SQL Error: " . $conn->error); // Display the SQL error message
  }

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $appmts[] = $row;
    }
  }

  // Redirect to the same page
  //header("Location: " . $_SERVER['PHP_SELF']);
}

// Display based on the "display" parameter
if (isset($_GET['display'])) {
  $filter_date = date("Y-m-d"); // Example format: 2023-10-29 

  $appmts = fetchTableData($conn, "tbl_appointments");
}

// Now $appmts contains the filtered or displayed data



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Smile 32</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />


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
  <link rel="stylesheet" href="css/services.css" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet" />

  <!-- Template Stylesheet -->
  <link href="css/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/add_doctors.css" />

</head>

<body>

  <div class="nav-admin">
    <?php include("admin_menu.php"); ?>
  </div>
  <!-- Modal for editing doctor details -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit appointments Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <script>
            function showEditForm(a_id, status) {
              var modal = document.getElementById("editModal");
              var modalBody = modal.querySelector(".modal-body");
              //alert('Clicked! Appointment ID: ' + a_id + ', Status: ' + status);

              var form = `
                <form action="" method="post">
                    <input type="hidden" name="appointment_id" value="${a_id}">
                    <label for="status">Select Status:</label>
                    <select id="status" name="status">
                        <option value="${status}" selected>${status}</option>
                        <option value="pending">Pending</option>
                        <option value="completed">completed</option>
                        <option value="rejected">Rejected</option>
                    </select><br>
                    <button type="submit" name="update_status" class="btn btn-success">Update</button>
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
  <div class="appmt1">


    <div class="appmt-container">
      <h1>Appointment</h1>
      <form class="d-flex" onsubmit="handleSearch(); return false;">
        <div class="d-flex search-container">
          <div class="d-flex">
            <input id="searchInput" class="form-control me-2 btn-outline-success custom-input" type="search" placeholder="Search" aria-label="Search">
          </div>
          <div class="d-flex">
            <button class="btn btn-outline-success  custom-input2" type="submit">Search</button>
          </div>
        </div>
      </form>
      <div>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

          <label for="filter">filter By Date</label>
          <input type="date" name="filter_date" value="<?php echo $filter_date; ?>">
          <input type="submit" name="filter" value="filter">
        </form>

      </div>

      <div class="table-responsive ">
        <table class="table table-success table-striped">
          <thead>
            <tr>
              <th>appointment_id</th>
              <th>Patient Name</th>
              <th>doctor Name</th>
              <th>service Name</th>
              <th>Patient Email</th>
              <th>Needed Section</th>
              <th>Needed Time</th>
              <th>Needed Date</th>
              <th>Booked Date & Time</th>
              <th>Status</th>

            </tr>
          </thead>
          <tbody id="table-body">
            <?php
            if (!empty($appmts)) {
              foreach ($appmts as $index => $appmt) : ?>
                <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                  <td>
                    <?php
                    $a_id = $appmt['appointment_id'];

                    echo $a_id; ?>
                  </td>
                  <td><?php
                      $p_id = $appmt['patient_id'];
                      $names = fetchName($conn, $p_id, 'patient_id', "tbl_patient");
                      foreach ($names as $index => $name) :
                        echo $name['full_name'];
                        $fullname = $name['full_name'];
                      endforeach;
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

                      $p_email = $appmt['patient_email'];
                      echo $p_email;
                      ?></td>
                  <td><?php
                      $section = $appmt['section'];
                      echo $section;
                      ?></td>
                  <td><?php
                      $appo_time = $appmt['appo_time'];
                      echo $appo_time;
                      ?></td>

                  <td><?php
                      $appointmentneed_date = $appmt['appointmentneed_date'];
                      echo $appointmentneed_date;
                      ?></td>
                  <td><?php
                      $applied_date = $appmt['created_at'];
                      echo $applied_date;
                      ?></td>
                  <td><?php
                      $status = $appmt['status'];
                      echo '<a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(' . $a_id . ', \'' . $status . '\')">' . $status . '</a>';



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


  </div>
  <!-- The  View Documents
  <div id="documentModal" class="modal">
    <div class="modal-content table-success table-striped" style="width:50%;margin:108px;margin-left :350px;">
      <div class="row">
        <div id="documentImage" class="d-flex col-md-6 justify-content-center" style="width:90%;">
          <img  src="https://img.icons8.com/color/200w/european-dragon.png" class="img-fluid rounded-circle">

        </div>
        <div class="d-flex col-sm-3" style="width:50px;">
          <span class="close" onclick="closeDocumentPopup()" style="font-size:47px;">&times;</span>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3" >
          <label for="appmt-id">APPOINTMENT ID</label>
          <?php echo $a_id; ?>
        </div>
        <div class="col-sm-3">
          <label for="P_NAME">PATIENT NAME</label>
          <?php echo $fullname; ?>
        </div>
        <div class="col-sm-3">
          <label for="D-NAME">DOCTOR NAME</label>
          <?php echo $d; ?>
        </div>
        <div class="col-sm-3">
          <label for="D-NAME">SERVICE NAME</label>
          <?php echo $s; ?>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
          <label for="">NEEDED TIME </label>
          <?php echo $A_start_time . "-" . $A_end_time; ?>
        </div>
        <div class="col-sm-3">
          <label for="D-NAME">NEEDED DATE</label>
          <?php echo $need_date; ?>
        </div>
        <div class="col-sm-3">
          <label for="">STATUS</label><br>
          <?php echo $status; ?>
        </div>

      </div>
      <div class="row">
        <div class="col-sm-12">
          
        </div>
      </div>
    </div>
  </div>-->
  </div>
  <div class="appmt2">
    <div style="width: 100%;  background-image: url(img/carousel-2.jpg);  background-size: cover;  background-position: center;  background-repeat: no-repeat;  height: 80vh;">
      <div class="appmt-form">
        <div class="box">
          <div class="container ">
            <center>
              <h2 class="mt-5">Appointment </h2>
            </center>
            <form action="process_appointment.php" method="POST" style="color: black;text-align: center; font-family: sans-serif;margin-top: 55px;">
              <div class="row box-space">
                <div class="form-group">
                  <label for="name">Your Name:</label>
                  <input type="text" class="form-control" name="name" id="name" required>

                </div>
                <div class="col-md-6 ">

                  <div class="form-group">
                    <label for="doctor">Select Doctor:</label>
                    <select id="doctor_id" class="form-control" name="doctor_id" required>
                      <?php
                      foreach ($doctors as $index => $doctor) :

                        $doctor_id = $doctor["doctor_id"];
                        $doctor_name = $doctor["doctor_name"];
                        echo "<option value=\"$doctor_id\">$doctor_name</option>";


                      endforeach;


                      ?>
                    </select>
                  </div>
                  <div class="form-group fg">
                    <label for="date">Appointment Date:</label>
                    <input type="date" class="form-control" name="date" id="date" required>
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="service">Select Service:</label>
                    <select class="form-control" name="doctor_id" required>
                      <?php
                      foreach ($services as $index => $service) :

                        $service_id = $service["service_id"];
                        $service_name = $service["service_name"];
                        echo "<option value=\"$service_id\">$service_name</option>";


                      endforeach;


                      ?>
                    </select>
                  </div>
                  <div class="form-group fg">
                    <label for="time">Appointment Time:</label>
                    <select class="form-control" name="doctor_id" required>
                      <?php
                      foreach ($doctorTimes as $index => $doctorTime) :
                        $sql = "SELECT * FROM tbl_doctortime WHERE  doctor_id = '$doctor_id' AND status = 'Active'";
                        $result = $conn->query($sql);
                        $doctorTime = [];

                        if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $doctorTime[] = $row;
                          }
                        }
                        $doctorTime = $doctorTime["doctortime_id"];
                        $starttime = $doctorTime["A_start_time"];
                        $endtime = $doctorTime["A_end_time"];


                        echo "<option value=\"$service_id\">$service_name</option>";


                      endforeach;


                      ?>
                    </select>
                  </div>
                </div>
              </div><br><br>
              <center>
                <button type="submit" class="btn btn-primary">Fix Appointment</button>
              </center>
            </form>
          </div>
        </div>


      </div>
    </div>
  </div>





  <script>
    function handleSearch() {
      var searchInput = document.getElementById("searchInput").value.toLowerCase();
      var tableRows = document.querySelectorAll(".table-row");

      tableRows.forEach(function(row) {
        var rowData = row.innerText.toLowerCase();
        if (rowData.includes(searchInput)) {
          row.style.display = "table-row";
        } else {
          row.style.display = "none";
        }
      });
    }
  </script>
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
  <!-- Add this script at the end of your HTML, before the closing </body> tag -->






  <!-- Template Javascript -->
  <script src="js/main.js"></script>

</body>

</html>