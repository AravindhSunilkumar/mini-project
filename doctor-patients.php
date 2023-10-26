<?php
session_start();
include("connection.php");
function fetchTableData($conn, $tableName, $service)
{
    
    $sql = "SELECT * FROM $tableName WHERE services='$service'";
    if ($service == '5') {
        $sql = "SELECT * FROM $tableName";
    }
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}
if ((isset($_GET['id'])) && ($_GET['id'] == '1')) {
    $patients = fetchTableData($conn, 'tbl_patient', '1');
} elseif ((isset($_GET['id'])) && ($_GET['id'] == '2')) {

    $patients = fetchTableData($conn, 'tbl_patient', '2');
} elseif ((isset($_GET['id'])) && ($_GET['id'] == '3')) {
    $patients = fetchTableData($conn, 'tbl_patient', '3');
} elseif ((isset($_GET['id'])) && ($_GET['id'] == '4')) {
    $patients = fetchTableData($conn, 'tbl_patient', '4');
} else {
    $patients = fetchTableData($conn, 'tbl_patient', '5');
}

// Handle doctor update
if (isset($_POST['update_patient'])) {
    $patient_id = $_POST['patientId'];
    $prescription = $_POST['prescription'];
    $Details = $_POST['Details'];
    $status = $_POST['status'];


    $update_sql = "UPDATE tbl_patient SET 
                   prescription = '$prescription', 
                   Details = '$Details', 
                   status = '$status'
                   WHERE patient_id = '$patient_id'";

    if ($conn->query($update_sql) === TRUE) {
        // Update successful
        // echo "Update successful!";
        //header("Location: doctors_list.php");
        //exit();
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
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // Get the image path and delete the image file
    $get_image_sql = "SELECT profile_picture FROM tbl_patient WHERE patient_id = '$patient_id'";
    $image_result = $conn->query($get_image_sql);
    if ($image_result->num_rows === 1) {
        $image_path = $image_result->fetch_assoc()['profile_picture'];
        if ($image_path && file_exists($image_path)) {
            unlink($image_path); // Delete the image file
        }
    }

    // Delete the doctor from the database
    $delete_sql = "DELETE FROM tbl_patient WHERE patient_id = '$patient_id'";
    if ($conn->query($delete_sql) === TRUE) {
        // Deletion successful
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Deletion failed
        echo "Error deleting record: " . $conn->error;
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


                <a href="index.html" class="nav-item nav-link">Home</a>
                <a href="doctor-patients.php?id=<?= 1 ?>" class="nav-item nav-link">Cosmetic Dentistry</a>
                <a href="doctor-patients.php?id=<?= 2 ?>" class="nav-item nav-link">Dental Implant</a>
                <a href="doctor-patients.php?id=<?= 3 ?>" class="nav-item nav-link">Dental Bridges</a>
                <a href="doctor-patients.php?id=<?= 4 ?>" class="nav-item nav-link">Teeth Whitening</a>






            </div>
            <?php if (isset($_SESSION['name'])) { ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><img src="img/person.png" alt="icon" class="icon" /></a>
                    <div class="dropdown-menu m-0">
                        <a href="User.php" class="dropdown-item"><?php echo $_SESSION['name'] ?></a>
                        <a href="logout.php" class="dropdown-item">SignOut</a>
                    </div>
                </div>
            <?php }  ?>



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
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Services</th>
                        <th>prescription</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>update </th>


                    </tr>
                </thead>
                <?php if (!empty($patients)) : ?>
                <tbody>
                    <?php foreach ($patients as $index => $patient) : ?>
                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                            <td><img src="<?= $patient['profile_picture']; ?>" alt="Not available" class="img-icon"></td>
                            <td><?= $patient['full_name']; ?></td>
                            <td><?= $patient['date_of_birth']; ?></td>
                            <td><?= $patient['gender']; ?></td>
                            <td>

                                <?php if ($patient['services'] == '1') {
                                    echo "Cosmetic Dentistry";
                                } elseif ($patient['services'] == '2') {
                                    echo "Dental Implants";
                                } elseif ($patient['services'] == '3') {
                                    echo 'Dental Bridges';
                                } elseif ($patient['services'] == '4') {
                                    echo 'Teeth Whitening';
                                }
                                ?></td>
                            <td><?= $patient['prescription']; ?></td>
                            <td><?= $patient['Details']; ?></td>
                            <td><?= $patient['status']; ?></td>
                            <div class="d-flex">
                                <!-- ... your existing table rows ... -->
                                <td class="wrapper">
                                    <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(
        '<?= $patient['patient_id']; ?>',
        '<?= $patient['prescription']; ?>',
        '<?= $patient['Details']; ?>',
        '<?= $patient['status']; ?>'
        
    )">Edit</a>
                                    <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&patient_id=<?= $patient['patient_id']; ?>" class="btn btn-danger">Del</a>
                                </td>

                            </div>
                        <?php endforeach; ?>
                        <?php else : ?>
                           
                            <td>No patients to display. </td>
<?php endif; ?>

                </tbody>
               
            </table>

        </div>
    </div>
    <!-- Modal for editing doctor details -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit patient Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <script>
                        function showEditForm(patientId, prescription, Details, status) {
                            var modal = document.getElementById("editModal");
                            var modalBody = modal.querySelector(".modal-body");

                            var form = `
            <form action="" method="post">
                <input type="hidden" name="patientId" value="${patientId}">
                <label>prescription:</label>
                <input type="text" name="prescription" value="${prescription}" required><br>
                <label>Details:</label>
                <input type="text" name="Details" value="${Details}" required><br>
                <label for="status">Select Status:</label>
                    <select id="status" name="status">
                        <option value="${status}" selected>${status}</option>
                        <option value="completed">completed</option>
                        <option value="pending">pending</option>
                        <option value="rejected">Rejected</option>
                    </select><br>
                <button type="submit" name="update_patient" class="btn btn-success">Update</button>
                
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