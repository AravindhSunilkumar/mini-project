<?php
include('connection.php');

// Fetch data from the database table
$sql = "SELECT * FROM tbl_doctors";
$result = $conn->query($sql);
$doctors = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

// Handle doctor update
if (isset($_POST['update_doctor'])) {
    $doctor_id = $_POST['doctor_id'];
    $new_doctor_name = $_POST['new_doctor_name'];
    $new_age = $_POST['new_age'];
    $new_gender = $_POST['new_gender'];
    $new_services = $_POST['new_services'];
    $new_qualification = $_POST['new_qualification'];

    $update_sql = "UPDATE tbl_doctors SET 
                   doctor_name = '$new_doctor_name', 
                   age = '$new_age', 
                   gender = '$new_gender', 
                   services = '$new_services', 
                   qualification = '$new_qualification' 
                   WHERE doctor_id = '$doctor_id'";

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
if (isset($_POST['update_image'])) {
    $doctor_id = $_POST['doctor_id'];

    // Check if a file was uploaded successfully
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "img/doctors/";
        $file_extension = strtolower(pathinfo($_FILES["new_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file)) {
            // Delete previous image if it exists
            $get_previous_image_sql = "SELECT doctor_image FROM tbl_doctors WHERE doctor_id = '$doctor_id'";
            $previous_image_result = $conn->query($get_previous_image_sql);
            if ($previous_image_result->num_rows === 1) {
                $previous_image = $previous_image_result->fetch_assoc()['doctor_image'];
                if ($previous_image && file_exists($previous_image)) {
                    unlink($previous_image);
                }
            }

            // Update the doctor's image path in the database
            $update_image_sql = "UPDATE tbl_doctors SET doctor_image = '$target_file' WHERE doctor_id = '$doctor_id'";
            if ($conn->query($update_image_sql) === TRUE) {
                // Image update successful
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                // Image update failed
                echo "Error updating image in database: " . $conn->error;
            }
        } else {
            // Failed to move the uploaded file
            echo "Failed to move uploaded file.";
        }
    }
}
// ... your existing code ...

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['doctor_id'])) {
    $doctor_id = $_GET['doctor_id'];

    // Get the image path and delete the image file
    $get_image_sql = "SELECT doctor_image FROM tbl_doctors WHERE doctor_id = '$doctor_id'";
    $image_result = $conn->query($get_image_sql);
    if ($image_result->num_rows === 1) {
        $image_path = $image_result->fetch_assoc()['doctor_image'];
        if ($image_path && file_exists($image_path)) {
            unlink($image_path); // Delete the image file
        }
    }

    // Delete the doctor from the database
    $delete_sql = "DELETE FROM tbl_doctors WHERE doctor_id = '$doctor_id'";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- ... your head content ... -->

</head>

<body class="doctor-list">
    <?php include('admin_menu.php'); ?>
    <div class="doctor-container justify-content-center align-items-center">
        <!-- ... your existing content ... -->
        <div class="d-flex">

            <div class="container">
                <div class="row">
                    <div class="col">
                        <h4 class="dwid mb-0 ">Doctors List</h4>
                    </div>
                    <div class="col">
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
                    </div>
                    <div class="col justify-content-center align-items-center">
                        <div class="right-corner">
                            <form action="add_doctors.php" method="post">
                                <input class="btn btn-success" type="submit" value="ADD">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="col-* table table-success table-striped shadow-lg t-hover">
                <thead>
                    <tr>
                        <th>Doctor ID</th>
                        <th>Doctor Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Services</th>
                        <th>Qualification</th>
                        <th>Doctor Image</th>
                        <th>Doctor Joined Date</th>
                        <th>Update</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $index => $doctor) : ?>
                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                            <td><?= $doctor['doctor_id']; ?></td>
                            <td><?= $doctor['doctor_name']; ?></td>
                            <td><?= $doctor['age']; ?></td>
                            <td><?= $doctor['gender']; ?></td>
                            <td><?= $doctor['services']; ?></td>
                            <td><?= $doctor['qualification']; ?></td>
                            <td class="flex">
                                <div class="image-container">
                                    <img src="<?= $doctor['doctor_image']; ?>" alt="" class="img-icon">
                                    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" class="upload-form">
                                        <input type="file" name="new_image" accept="image/*">
                                        <input type="hidden" name="doctor_id" value="<?= $doctor['doctor_id']; ?>">
                                        <input class="btn btn-primary" type="submit" name="update_image" value="Change Image">
                                    </form>
                                </div>
                            </td>

                            <td><?= $doctor['doctor_created_at']; ?></td>
                            <div class="d-flex">
                                <!-- ... your existing table rows ... -->
                                <td class="wrapper">
                                    <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(
        <?= $doctor['doctor_id']; ?>,
        '<?= $doctor['doctor_name']; ?>',
        '<?= $doctor['age']; ?>',
        '<?= $doctor['gender']; ?>',
        '<?= $doctor['services']; ?>',
        '<?= $doctor['qualification']; ?>'
    )">Edit</a>
                                    <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&doctor_id=<?= $doctor['doctor_id'] ?>" class="btn btn-danger">Del</a>
                                </td>

                            </div>

                        <?php endforeach; ?>
            </table>
        </div>

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

</body>

</html>