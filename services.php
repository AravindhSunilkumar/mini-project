<?php
include("connection.php");
if (isset($_POST["add_service"])) {
  addService();
}
function addService()
{
  global $conn;
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_service"])) {
      $service_name = $_POST["service_name"];
      $service_info = $_POST["additional_info"];
      $check_sql = "SELECT * FROM tbl_services WHERE service_name = '$service_name'";
      $check_result = $conn->query($check_sql);
      if ($check_result->num_rows > 0) {
        echo '<script>alert("Service with the same name already exists.");</script>';
      } else {
        if (isset($_FILES["service_image"]) && $_FILES["service_image"]["error"] == UPLOAD_ERR_OK) {
          $targetDir = "img/services/";
          $targetFile = $targetDir . basename($_FILES["service_image"]["name"]);
          $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

          // Generate a unique filename
          $newFilename = uniqid() . '.' . $fileType;

          // Move the uploaded file to the target directory
          if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $targetDir . $newFilename)) {
            // File was successfully uploaded
            // Now insert the file address into the table
            $fileAddress = $targetDir . $newFilename;
            if (isset($service_name) && isset($service_info) &&  isset($fileAddress)) {

              // Insert data into the table
              $sql = "INSERT INTO tbl_services (service_name,service_image, additional_info,status ) 
                    VALUES (?, ?, ?,'Active')";

              $stmt = $conn->prepare($sql);
              $stmt->bind_param("sss", $service_name, $fileAddress, $service_info);



              if ($stmt->execute()) {
                echo '<script>
        var confirmed = confirm("Service added successfully. Click OK to continue.");
        if (confirmed) {
            window.location.href = "services.php";
        }
    </script>';
              } else {
                echo "Error inserting data: " . $stmt->error;
              }


              $stmt->close();
              $conn->close();
            } else {
              echo "<script>alert(Please fill all fields!  )</script>";
            }
          } else {
            echo "<script>alert(Error moving file to target directory.)</script>";
          }
        } else {
          echo "Error uploading file.";
        }
      }
    }
  }
}
$sql = "SELECT * FROM tbl_services";
$result = $conn->query($sql);
$services = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $services[] = $row;
  }
}

// Handle doctor update
if (isset($_POST['update_service'])) {
  $service_id = $_POST['service_id'];

  $service_name = $_POST['service_name'];
  $additional_info = $_POST['additional_info'];





  // Update data in the table
  $update_sql = "UPDATE tbl_services SET 
                   service_name = ?,
                   additional_info = ?
                   WHERE service_id = ?";

  $stmt = $conn->prepare($update_sql);
  $stmt->bind_param("ssi", $service_name, $additional_info, $service_id);

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
  if ($_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
    // Get the service ID from the form
    $service_id = $_POST['service_id'];

    // Define the upload directory and target file name
    $upload_dir = 'img/service_images/';
    $target_file = $upload_dir . basename($_FILES['new_image']['name']);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['new_image']['tmp_name'], $target_file)) {
      $get_image_sql = "SELECT service_image FROM tbl_services WHERE service_id = ?";
      $stmt = $conn->prepare($get_image_sql);
      $stmt->bind_param("i", $service_id);
      $stmt->execute();
      $stmt->bind_result($old_image_path);
      $stmt->fetch();
      $stmt->close();

      // Delete the old image if it exists
      if ($old_image_path && file_exists($old_image_path)) {
        unlink($old_image_path);
      }
      // Image upload successful

      // Update the database with the new image file name
      $new_image_name = $target_file; // Full path to the image

      // Update the service_image column in the tbl_services table
      $update_image_sql = "UPDATE tbl_services SET service_image = ? WHERE service_id = ?";

      // Create a prepared statement
      $stmt = $conn->prepare($update_image_sql);

      // Bind parameters
      $stmt->bind_param("si", $new_image_name, $service_id);

      if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
        // Image update successful
        // Delete the previous image (if it exists)


        $stmt->close();
      } else {
        echo "Error updating record: " . $stmt->error;
      }
    } else {
      echo "Error moving uploaded file.";
    }
  } else {
    echo "Error uploading file.";
  }
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



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
  <title>Services</title>
  <link rel="stylesheet" href="./css/services.css" />
  <link rel="stylesheet" href="./css/style.css" />
</head>



<body>
  <div class="menu">
    <?php include("admin_menu.php"); ?>
  </div>

  <div class="services">
    <div class="service-container1">
      <div class="service-container">
        <br /><br />
        <center>
          <h1><u>Add New Services</u></h1>
          <br />
        </center>

        <!-- Form for managing services, doctor availability, and timeslots -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
          <!-- Service Management -->
          <center>
            <h3>Service Management</h3>
          </center>
          <center>
            <div class="flex">

              <label for="service_name">Service Name:</label>
              <input type="text" id="service_name" name="service_name" required />
              <label for="service_image" class="new">Service Image:</label>
              <input type="file" class="new2" id="service_image" name="service_image" required />

              <label for="additional_info">Additional Info:</label>
              <textarea id="additional_info" name="additional_info" rows="1" required></textarea><br><br><br>
            </div>
          </center>
          <center>
            <input type="submit" class="btn btn-primary py-2 px-4 ms-3" name="add_service" value="Submit" />
          </center>
        </form>
      </div>
    </div>
    <div class="service-list">
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
              <th>Service ID </th>
              <th>Service Name</th>
              <th>Service Image</th>
              <th>Additional Info</th>
              <th>Status</th>
              <th>created_at</th>
              <th>update</th>


            </tr>
          </thead>
          <tbody>
            <?php foreach ($services as $index => $services) : ?>
              <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                <td><?= $services['service_id']; ?></td>
                <td><?= $services['service_name']; ?></td>

                <td class="flex">
                  <div class="image-container">
                    <img src="<?= $services['service_image']; ?>" alt="" class="img-icon">
                    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" class="upload-form">
                      <input type="file" name="new_image" accept="image/*">
                      <input type="hidden" name="service_id" value="<?= $services['service_id']; ?>">
                      <input class="btn btn-primary" type="submit" name="update_image" value="Change Image">
                    </form>
                  </div>
                </td>
                

                <td><?= $services['additional_info']; ?></td>
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




                <td><?= $services['created_at']; ?></td>
                <div class="d-flex">
                  <!-- ... your existing table rows ... -->
                  <td class="wrapper">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-info" onclick="showEditForm(
        <?= $services['service_id']; ?>,
        '<?= $services['service_name']; ?>',
        '<?= $services['additional_info']; ?>'
        
    )">Edit</a>

                    <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&service_id=<?= $services['service_id']; ?>" class="btn btn-danger">Del</a>
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
              <h5 class="modal-title" id="editModalLabel">Edit services Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- JavaScript to handle edit form display and submission -->
              <script>
                function showEditForm(serviceId, serviceName, additionalInfo) {
                  var modal = document.getElementById("editModal");
                  var modalBody = modal.querySelector(".modal-body");

                  var form = `
      <form action="" method="post">
        <input type="hidden" name="service_id" value="${serviceId}">
        <label>Service Name:</label>
        <input type="text" name="service_name" value="${serviceName}" required><br>
        <label>Additional Info:</label>
        <input type="text" name="additional_info" value="${additionalInfo}" required><br>
        <button type="submit" name="update_service" class="btn btn-success">Update</button>
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
  <script src="js/main.js"></script>
  <!-- Template Javascript -->
  </div>
</body>

</html>