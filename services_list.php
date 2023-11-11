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
$services = fetchTableData($conn, "tbl_price_packages");
function tableData($conn, $tableName)
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


function fetchName($conn, $id, $t_id, $tableName)
{
    $sql = "SELECT * FROM $tableName WHERE $t_id = $id ";
    $result = $conn->query($sql);
    global $d;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $d = $row['service_name'];
        }
    }

    return $d;
}
// Handle doctor update
if (isset($_POST['update_package'])) {
    $package_id = $_POST['package_id'];

    $package_name = $_POST['package_name'];
    $price = $_POST['price'];
    $additional_info = $_POST['additional_info'];





    // Update data in the table
    $update_sql = "UPDATE tbl_price_packages SET 
                     package_name = ?,
                     price =?,
                     package_discription = ?
                     WHERE package_id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $package_name, $price, $additional_info, $package_id);

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








// ... your existing code ...

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['service_id'])) {
    $service_id  = $_GET['service_id'];

    // Get the image path and delete the image file
    $get_image_sql = "SELECT service_image FROM tbl_services WHERE service_id = '$service_id'";
    $image_result = $conn->query($get_image_sql);
    if ($image_result->num_rows === 1) {
        $image_path = $image_result->fetch_assoc()['service_image'];
        if ($image_path && file_exists($image_path)) {
            unlink($image_path); // Delete the image file
        }
    }

    // Delete the doctor from the database
    $delete_sql = "DELETE FROM tbl_services WHERE service_id  = '$service_id'";
    if ($conn->query($delete_sql) === TRUE) {
        // Deletion successful
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Deletion failed
        echo "Error deleting record: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $service_id = $_POST["service_id"];
    $new_status = $_POST["new_status"];

    // Update the service status in the database
    $update_sql = "UPDATE tbl_services SET status = ? WHERE service_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $service_id);

    if ($stmt->execute()) {
        // Status updated successfully
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
}



// Check if the form is submitted
if (isset($_POST['Add_pack'])) {
    // Get the form data
    $package_name = $_POST['package_name'];
    $service_id = $_POST['service_id'];
    $price = $_POST['price'];

    // Validate the data if needed

    // Insert data into the database
    $inserted = insertData($conn, "tbl_packages", [
        'package_name' => $package_name,
        'service_id' => $service_id,
        'price' => $price
    ]);

    // Check if the insertion was successful
    if ($inserted) {
        echo "Data inserted successfully!";
    } else {
        echo "Error inserting data.";
    }
}

// Replace 'insertData' with your actual function to insert data into the database
function insertData($conn, $tableName, $data)
{
    // Assuming you have a function to handle database insertions
    // The function should handle SQL injection prevention (e.g., use prepared statements)
    
    $columns = implode(', ', array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";

    $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";

    // Execute the SQL query
    $result = $conn->query($sql);

    return $result;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/services.css">
    <title>Document</title>
</head>

<body>
    <?php include('admin_menu.php'); ?>
    <div class="row">
        <h4>Service List</h4>
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
    <div class="table-responsive">
        <table class="col-* table table-success table-striped shadow-lg">
            <thead>
                <tr>
                    <th>Package ID </th>
                    <th>Service Name</th>
                    <th>Package Name</th>
                    <th>Price </th>
                    <th>Package Discription</th>
                    <th>Status</th>
                    <th>update</th>


                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $index => $services) : ?>
                    <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                        <td><?= $services['package_id']; ?></td>
                        <td><?php $s_id = $services['service_id'];
                            $s_name = fetchName($conn, $s_id, 'service_id', "tbl_services");
                            echo $s_name;
                            ?></td>
                        <td><?= $services['package_name']; ?></td>
                        <td><?= $services['price']; ?></td>
                        <td><?= $services['package_discription']; ?></td>





                        <td>
                            <div class="onoffswitch">
                                <input type="checkbox" class="onoffswitch-checkbox" id="serviceSwitch<?= $services['service_id']; ?>" <?= $services['status'] === 'Active' ? 'checked' : ''; ?>>
                                <label class="onoffswitch-label swi" for="serviceSwitch<?= $services['service_id']; ?>" onclick="toggleServiceStatus(<?= $services['service_id']; ?>, '<?= $services['status']; ?>')">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </td>
                        <!-- JavaScript to handle status toggle -->
                        <script>
                            function toggleServiceStatus(serviceId, currentStatus) {
                                var newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';

                                var confirmation = confirm("Are you sure you want to change the status to " + newStatus + "?");
                                if (confirmation) {
                                    // Send an AJAX request to update the status
                                    $.ajax({
                                        type: "POST",
                                        url: "update_status.php", // Replace with the actual PHP script that updates the status
                                        data: {
                                            service_id: serviceId,
                                            new_status: newStatus
                                        },
                                        success: function(response) {
                                            if (response === 'success') {
                                                // Status updated successfully
                                                location.reload(); // Reload the page or update the status in the table dynamically
                                            } else {
                                                alert("Error updating status: " + response);
                                            }
                                        }
                                    });
                                }
                            }
                        </script>





                        <div class="d-flex">
                            <!-- ... your existing table rows ... -->
                            <td class="wrapper">
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-info" onclick="showEditForm(
        <?= $services['package_id']; ?>,
        '<?= $services['package_name']; ?>',
        '<?= $services['price']; ?>',
        '<?= $services['package_discription']; ?>'
        
    )">Edit</a>

                                <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&service_id=<?= $services['package_id']; ?>" class="btn btn-danger">Del</a>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Package Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- JavaScript to handle edit form display and submission -->
                    <script>
                        function showEditForm(packageId, packageName, price, packagediscription) {
                            var modal = document.getElementById("editModal");
                            var modalBody = modal.querySelector(".modal-body");

                            var form = `
      <form action="" method="post">
        <input type="hidden" name="package_id" value="${packageId}">
        <label>Service Name:</label>
        <input type="text" name="package_name" value="${packageName}" required><br>
        <label>Service Name:</label>
        <input type="text" name="price" value="${price}" required><br>
        <label>Additional Info:</label>
        <input type="text" name="additional_info" value="${packagediscription}" required><br>
        <button type="submit" name="update_package" class="btn btn-success">Update</button>
      </form>
    `;

                            modalBody.innerHTML = form;
                            $(modal).modal("show");

                            // Show a confirmation message after updating
                            var updateButton = modal.querySelector("[name='update_service']");
                            updateButton.addEventListener("click", function() {
                                alert("service information updated successfully!");
                            });
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
    </div>

    <!--add  new package-->
    <div class="d-flex justify-content-center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="Packagename">Package Name</label>
            <input type="text" name="package_name" id="packageName" required>
            <label for="servicename">Service Name</label>
            <select class=" bg-light border-0" name="doctor_id" style="height:54px;"  required>

                <?php
                $servs = tableData($conn, "tbl_services");
                foreach ($servs as $index => $service) :

                    $service_id = $service["service_id"];
                    $service_name = $service["service_name"];
                    echo "<option  value=\"$service_id\" >$service_name</option>";


                endforeach;


                ?>
            </select>
            <label for="price">Price</label>
            <input type="text" name="price" id="price" required>
            <input type="submit" value="Submit" name="Add_pack">
        </form>
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