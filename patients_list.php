<?php
include('connection.php');

// Fetch data from the database table
$sql = "SELECT * FROM tbl_patient";
$result = $conn->query($sql);
$patients = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

// Handle doctor update
if (isset($_POST['update_patient'])) {
    $patient_id = $_POST['patient_id'];
    $name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $allergyinfo = empty($_POST['allergy_info']) ? NULL : $_POST['allergy_info'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $phone = preg_replace('/\D/', '', $phone);

    // Update data in the table
    $update_sql = "UPDATE tbl_patient SET 
                   full_name = ?,
                   gender = ?,
                   date_of_birth = ?,
                   address = ?,
                   allergy_info = ?,
                   emergency_contact_phone = ? 
                   WHERE patient_id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssi", $name, $gender, $dob, $address, $allergyinfo, $phone, $patient_id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating data: " . $stmt->error;
    }


    // Redirect back to the doctor list page after updating
    //header("Location: doctors_list.php");
    //exit();
}
if (isset($_POST['update_image'])) {
    $patient_id = $_POST['patient_id'];

    // Check if a file was uploaded successfully
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "img/patients/";
        $file_extension = strtolower(pathinfo($_FILES["new_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file)) {
            // Delete previous image if it exists
            $get_previous_image_sql = "SELECT profile_picture FROM tbl_patient WHERE patient_id = '$patient_id'";
            $previous_image_result = $conn->query($get_previous_image_sql);
            if ($previous_image_result->num_rows === 1) {
                $previous_image = $previous_image_result->fetch_assoc()['profile_picture'];
                if ($previous_image && file_exists($previous_image)) {
                    unlink($previous_image);
                }
            }

            // Update the doctor's image path in the database
            $update_image_sql = "UPDATE tbl_patient SET profile_picture = '$target_file' WHERE patient_id = '$patient_id'";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/tooth.png" type="image/png">

    <title>Patient List</title>
    <!-- ... your head content ... -->



</head>

<body class="doctor-list ">
    <?php include('admin_menu.php'); ?>
    <div class="patient-list-container ">
        <div class="doctor-container justify-content-center align-items-center ">
            <!-- ... your existing content ... -->
            <div class="d-flex">

                <!--  <h4 class="dwid mb-0 ">Patient List</h4>
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

                <div class="right-corner">
                    <form action="add_doctors.php" method="post">
                        <input class="btn btn-success" type="submit" value="ADD">
                    </form>
                </div>-->
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h4 class="dwid mb-0 ">Patient List</h4>
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
                            <div class="right-corner d-flex justify-content-center"> 
                                <form action="add_patient.php" method="post">
                                    <input class="btn btn-success" type="submit" value="ADD">
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="col-* table table-success table-striped shadow-lg">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Address</th>
                            <th>profile_picture</th>
                            <th>Allergy info</th>
                            <th>Phone number</th>
                            <th>Created at </th>
                            <th>Update</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $index => $patient) : ?>
                            <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                                <td><?= $patient['patient_id']; ?></td>
                                <td><?= $patient['full_name']; ?></td>
                                <td><?= $patient['gender']; ?></td>
                                <td><?= $patient['date_of_birth']; ?></td>
                                <td><?= $patient['address']; ?></td>
                                <td class="flex">
                                    <div class="image-container">
                                        <img src="<?= $patient['profile_picture']; ?>" alt="" class="img-icon">
                                        <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" class="upload-form">
                                            <input type="file" name="new_image" accept="image/*">
                                            <input type="hidden" name="patient_id" value="<?= $patient['patient_id']; ?>">
                                            <input class="btn btn-primary" type="submit" name="update_image" value="Change Image">
                                        </form>
                                    </div>
                                </td>
                                <td><?= $patient['allergy_info']; ?></td>
                                <td><?= $patient['emergency_contact_phone']; ?></td>
                                <td><?= $patient['created_at']; ?></td>
                                <div class="d-flex">
                                    <!-- ... your existing table rows ... -->
                                    <td class="wrapper">
                                        <a href="javascript:void(0);" class="btn btn-info" onclick="showEditForm(
        <?= $patient['patient_id']; ?>,
        '<?= $patient['full_name']; ?>',
        '<?= $patient['gender']; ?>',
        '<?= $patient['date_of_birth']; ?>',
        '<?= $patient['address']; ?>',
        '<?= $patient['allergy_info']; ?>',
        '<?= $patient['emergency_contact_phone']; ?>',
        '<?= $patient['created_at']; ?>'
    )">Edit</a>

                                        <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&patient_id=<?= $patient['patient_id']; ?>" class="btn btn-danger">Del</a>
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
                            <h5 class="modal-title" id="editModalLabel">Edit Patient Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <script>
                                function showEditForm(patientId, fullName, gender, dob, address, allergyInfo, emergencyContactPhone, createdAt) {
                                    var modal = document.getElementById("editModal");
                                    var modalBody = modal.querySelector(".modal-body");

                                    var form = `
                                    <form action="" method="post">
                                        <input type="hidden" name="patient_id" value="${patientId}">
                                        <label>Full Name:</label>
                                        <input type="text" name="full_name" value="${fullName}" required><br>
                                        <label>Gender:</label>
                                        <input type="text" name="gender" value="${gender}" required><br>
                                        <label>Date of Birth:</label>
                                        <input type="text" name="date_of_birth" value="${dob}" required><br>
                                        <label>Address:</label>
                                        <input type="text" name="address" value="${address}" required><br>
                                        <label>Allergy Info:</label>
                                        <input type="text" name="allergy_info" value="${allergyInfo}" required><br>
                                        <label>Emergency Contact Phone:</label>
                                        <input type="text" name="phone" value="${emergencyContactPhone}" required><br>
                                        <button type="submit" name="update_patient" class="btn btn-success">Update</button>
                                    </form>
                                `;

                                    modalBody.innerHTML = form;
                                    $(modal).modal("show");

                                    // Show a confirmation message after updating
                                    var updateButton = modal.querySelector("[name='update_patient']");
                                    updateButton.addEventListener("click", function() {
                                        alert("Patient information updated successfully!");
                                    });
                                }
                            </script>
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

</body>

</html>