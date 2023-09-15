<?php
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

// Example usage:
$services = fetchTableData($conn, "tbl_services");
$appmts = fetchTableData($conn, "tbl_appointments");
$patients = fetchTableData($conn, "tbl_patient");
$doctors = fetchTableData($conn, "tbl_doctors");

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
      <div class="table-responsive ">
        <table class="table table-success table-striped">
          <thead>
            <tr>
              <th>appointment_id</th>
              <th>Patient Name</th>
              <th>doctor Name</th>

              <th>service Name</th>
              <th>Time Needed</th>
              <th>Needed Date</th>
              <th>Status</th>
              <th>Applied Date</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="appmt2">
    <div style="width: 100%;
    background-image: url(img/carousel-2.jpg);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100%;">
      <div class="appmt-form">

        <div class="container">
          <h2 class="mt-5">Appointment Fixing Form</h2>
          <form action="process_appointment.php" method="POST" style="color: black;
    text-align: center;
    font-family: sans-serif;">
            <div class="row">
              <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" class="form-control" name="name" id="name" required>

              </div>
              <div class="col-md-6">

                <div class="form-group">
                  <label for="doctor">Select Doctor:</label>
                  <select class="form-control" name="doctor" id="doctor">
                    <!-- Populate this dropdown with doctor options from your database -->
                    <option value="doctor1">Doctor 1</option>
                    <option value="doctor2">Doctor 2</option>
                    <!-- Add more options as needed -->
                  </select>
                </div>
                <div class="form-group">
                  <label for="date">Appointment Date:</label>
                  <input type="date" class="form-control" name="date" id="date" required>
                </div>

              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="service">Select Service:</label>
                  <select class="form-control" name="service" id="service">
                    <!-- Populate this dropdown with service options from your database -->
                    <option value="service1">Service 1</option>
                    <option value="service2">Service 2</option>
                    <!-- Add more options as needed -->
                  </select>
                </div>
                <div class="form-group">
                  <label for="time">Appointment Time:</label>
                  <select class="form-control" name="time" id="time">
                    <!-- Populate this dropdown with time options from your database -->
                    <option value="9AM">9AM</option>
                    <option value="10AM">10AM</option>
                    <!-- Add more options as needed -->
                  </select>
                </div>
              </div>
            </div>
            <center>
              <button type="submit" class="btn btn-primary">Fix Appointment</button>
            </center>
          </form>
        </div>


      </div>
    </div>
  </div>

</body>

</html>