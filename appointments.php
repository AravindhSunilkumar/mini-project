<?php
include("connection.php");
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
$appmts = fetchTableData($conn, "tbl_appointments");
$patients = fetchTableData($conn, "tbl_patient");
$doctors = fetchTableData($conn, "tbl_doctors");
$doctorTimes = fetchTableDoctorTimeData($conn, "tbl_doctorTime");

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
              <th>View</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($appmts as $index => $appmt) : ?>
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
                      echo $s_name['service_name'];
                      $s = $s_name['service_name'];
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
                    echo $status;
                    ?></td>
                <div class="d-flex">
                  <!-- ... your existing table rows ... -->

                  <td><a href="#" onclick="openDocumentPopup('img/about-1.jpg')">View Document</a></td>

                </div>

                <!-- JavaScript functions to open and close the documents -->
                <script>
                  function openDocumentPopup(imageSrc) {
                    var modal = document.getElementById("documentModal");
                    var image = document.getElementById("documentImage");

                    image.src = imageSrc;
                    modal.style.display = "block";
                  }

                  function closeDocumentPopup() {
                    var modal = document.getElementById("documentModal");
                    modal.style.display = "none";
                  }
                </script>
      </div>
    <?php endforeach; ?>
    </tr>
    </tbody>
    </table>

    </div>


  </div>
  <!-- The  View Documents-->
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
  </div>
  </div>
  <div class="appmt2">
    <div style="width: 100%;
    background-image: url(img/carousel-2.jpg);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 80vh;">
      <div class="appmt-form">
        <div class="box">
          <div class="container ">
            <center>
              <h2 class="mt-5">Appointment </h2>
            </center>
            <form action="process_appointment.php" method="POST" style="color: black;
              text-align: center;
               font-family: sans-serif;margin-top: 55px;">
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




  <!-- Template Javascript -->
  <script src="js/main.js"></script>

</body>

</html>