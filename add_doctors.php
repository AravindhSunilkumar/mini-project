<?php
include('connection.php');
$doctorName = "";
$qualification = "";
$services = "";
$gender = "";
$age = "";
function slot($conn, $day)
{
    $sql = "SELECT * FROM tbl_timeslot WHERE days=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $day);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['slot_id'];
    }
    
    $stmt->close();
    
    return $id;
}

if (isset($_POST["timeslot"])) {
    timeSlot();
}
function timeSlot()
{
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["timeslot"])) {
        $service_id = $_POST["servicename"];
        $doctor_id = $_POST["doctor_id"];
        $availability_days = isset($_POST["availability_days"]) ? $_POST["availability_days"] : [];
        $sqld = "SELECT * FROM tbl_doctortime WHERE service_id = $service_id AND doctor_id=$doctor_id";
        $result1 = mysqli_query($conn, $sqld);


        if (mysqli_num_rows($result1)) {
            echo '<script>
            if (confirm("Doctor time was already added ")) {
                window.location.href = "add_doctors.php";
            }
        </script>';
            
        } else {
            
        if (empty($availability_days)) {
            echo '<script>alert("Please choose at least one available day");</script>';
        } else {
            // Loop through selected days and insert into tbl_appointment
            foreach ($availability_days as $day) {
                // Check if checkboxes for morning, afternoon, and evening are checked
                $id = slot($conn, $day);
                //$morning_checked = isset($_POST[$day . "_morning"]) ? 1 : 0;
                //$afternoon_checked = isset($_POST[$day . "_afternoon"]) ? 1 : 0;
                //$evening_checked = isset($_POST[$day . "_evening"]) ? 1 : 0;
                if (isset($_POST[$day . "_morning"])) {
                    $m_active = "Active";
                } else {
                    $m_active = "deactive";
                }
                if (isset($_POST[$day . "_afternoon"])) {
                    $a_active = "Active";
                } else {
                    $a_active = "deactive";
                }
                if (isset($_POST[$day . "_afternoon"])) {
                    $e_active = "Active";
                } else {
                    $e_active = "deactive";
                }
                // Insert data into tbl_appointment
                $sql = "INSERT INTO tbl_doctortime (doctor_id, service_id, slot_id, morning, afternoon, evening, status, created_at) 
                    VALUES ( ?, ?, ?, ?, ?, ?, 'Active', NOW())";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiisss", $doctor_id, $service_id, $id, $m_active, $a_active, $e_active);

                if ($stmt->execute()) {
                    // Handle success
                } else {
                    echo "Error inserting data: " . $stmt->error;
                }

                $stmt->close();
                $m_active = "deactive";
                $a_active = "deactive";
                $e_active = "deactive";
                $id=""; 
            }

            echo '<script>
                    if (confirm("Doctor availability added successfully. Click OK to continue.")) {
                        window.location.href = "add_doctors.php";
                         }
                </script>';
        }

        $conn->close();
        }
    }
}


if (isset($_POST["add_doctor"])) {
    $doctorName = $_POST["doctorName"];
    $gender = $_POST["gender"];
    $qualification = $_POST["qualification"];

    // Check if a doctor with the same name already exists
    $check_sql = "SELECT * FROM tbl_doctors WHERE doctor_name = '$doctorName'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo '<script>alert("A doctor with the same name already exists. Please choose a different name.");</script>';
    } else {

        // Get form data
        $doctorName = $_POST["doctorName"];
        $age = $_POST["age"];
        $gender = $_POST["gender"];
        $services = $_POST["services"];
        $qualification = $_POST["qualification"];

        // Check if an image file is uploaded
        upload:
        if ($_FILES["doctorImage"]["error"] == 0) {
            $file = $_FILES["doctorImage"];

            // // Extract filename and extension
            $filename = $file["name"];
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // Check if the selected file is an image
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_ext, $allowed_extensions)) {
                echo '<script>alert("Selected file is not an image. Please select a valid image file.")</script>';
                exit;
            }

            // Check if the file size is within the allowed limit (500KB)
            $file_size = $file["size"];
            $max_size = 500 * 1024; // 500KB
            if ($file_size > $max_size) {
                echo '<script>alert("File size exceeds the allowed limit. Please select a smaller image file.")</script>';
                goto upload;
            }

            // Generate a new unique filename
            $new_filename = uniqid() . "." . $file_ext;

            // Specify the destination folder and path for the image
            $destination_folder = "img/doctors/";
            $destination_path = $destination_folder . $new_filename;

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($file["tmp_name"], $destination_path)) {
                // Insert data into the tbl_doctors table
                $currentDateTime = date('Y-m-d H:i:s');
                $sql = "INSERT INTO tbl_doctors (doctor_name, age, gender, services, qualification, doctor_image, doctor_created_at) VALUES ('$doctorName', $age, '$gender', '$services', '$qualification', '$destination_path', '$currentDateTime')";

                if ($conn->query($sql) === true) {
                    // Display success message or perform any other desired actions
                    echo '<script>
            var confirmed = confirm("Doctor added successfully. Click OK to continue.");
            if (confirmed) {
                window.location.href = "doctors_list.php";
            }
        </script>';
                } else {
                    echo '<script>alert("Error inserting data into the database. Please try again.");</script>';
                }
            } else {
                echo '<script>alert("Error uploading the image file. Please try again.");</script>';
            }
        } else {
            echo '<script>alert("No image file uploaded. Please select an image file.");</script>';
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>Document</title>
    <link rel="stylesheet" href="./css/services.css" />
    <link rel="stylesheet" href="css/add_doctors.css">

    <link rel="stylesheet" href="css/style.css">
</head>


<body class="add_doctors">
    <div class="header1">
        <?php include('admin_menu.php') ?>
    </div>

    <div class="fullcontainer">

        <div class="fullcontainer2">
            <div class="add-doctors">
                <center>

                    <h2>Add Doctor</h2><br><br>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">


                        <div class="form-group">
                            <label for="doctorName">Doctor Name:</label><br>
                            <input type="text" class="form-doctors" id="doctorName" name="doctorName" value="<?php echo $doctorName; ?>" required><br>
                        </div>
                        <div class="form-group">
                            <label for="age">Age:</label><br>
                            <input type="text" class="form-doctors" id="age" name="age" value="<?php echo $age; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender:</label><br>
                            <select id="gender" class="form-doctors" name="gender" value="<?php echo $gender; ?>" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="services">Services:</label><br>
                            <input type="text" class="form-doctors" id="services" name="services" value="<?php echo $services; ?>" required>
                        </div><br>
                        <div class="form-group">
                            <label for="qualification">Qualification:</label>
                            <select id="qualification" class="form-doctors" name="qualification" value="<?php echo $qualification; ?>" required><br>
                                <option value="MDS">MDS</option>
                                <option value="BDS">BDS</option>
                            </select>
                        </div><br>




                        <div class="form-group">
                            <label for="doctorImage">Doctor Image:</label>
                            <input type="file" id="doctorImage" name="doctorImage" accept="image/*"><br><br>
                            <input type="submit" name="add_doctor" value="Add Doctor">
                        </div>
                    </form>
                </center>
            </div>

        </div>
        <div class="service-container1">
            <div class="doctors service-container">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <!-- Doctor Availability -->
                    <center>
                        <h3>Doctor Availability</h3>
                    </center>
                    <label for="servicename">Service Name:</label>
                    <select id="service_id" name="servicename" required>
                        <?php

                        // Fetch doctor IDs and names from tbl_doctors
                        $sql = "SELECT * FROM tbl_services";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $service_id = $row["service_id"];
                                $service_name = $row["service_name"];
                                echo "<option value=\"$service_id\">$service_name</option>";
                            }
                        } else {
                            echo "<option value=\"\">No doctors available</option>";
                        }


                        ?>
                    </select>
                    <br /><br />

                    <label for="doctor_id">Select Doctor:</label>
                    <select id="doctor_id" name="doctor_id" required>
                        <?php

                        // Fetch doctor IDs and names from tbl_doctors
                        $sql = "SELECT * FROM tbl_doctors";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $doctor_id = $row["doctor_id"];
                                $doctor_name = $row["doctor_name"];
                                echo "<option value=\"$doctor_id\">$doctor_name</option>";
                            }
                        } else {
                            echo "<option value=\"\">No doctors available</option>";
                        }


                        ?>
                    </select>
                    <br /><br />

                    <div class="d-flex">
                        <label for="available_days">Available Days:</label> <label for="" class="range">Time Start & Ends</label>
                    </div>
                    <!--<div class="checkbox">

                        <input type="checkbox" id="monday" name="availability_days[]" value="Monday" />
                        <label for="monday">Monday</label>

                        <input type="text" id="monday_start"  name="Monday_start" placeholder="starting time" />
                        <input type="text" id="monday_end" name="Monday_end" placeholder="Ending time" />

                        <input type="checkbox" id="tuesday" name="availability_days[]" value="Tuesday" />
                        <label for="tuesday">Tuesday</label>
                        <input type="text" id="tuesday_start" name="Tuesday_start" placeholder="starting time" />
                        <input type="text" id="tuesday_end" name="Tuesday_end" placeholder="Ending time" />

                        <input type="checkbox" id="Wednesday" name="availability_days[]" value="Wednesday" />
                        <label for="Wednesday">Wednesday</label>
                        <input type="text" id="Wednesday_start" name="Wednesday_start" placeholder="starting time" />
                        <input type="text" id="Wednesday_end" name="Wednesday_end" placeholder="Ending time" />

                        <input type="checkbox" id="Thursday" name="availability_days[]" value="Thursday" />
                        <label for="Thursday">Thursday</label>
                        <input type="text" id="Thursday_start" name="Thursday_start" placeholder="starting time" />
                        <input type="text" id="Thursday_end" name="Thursday_end" placeholder="Ending time" />

                        <input type="checkbox" id="Friday" name="availability_days[]" value="Friday" />
                        <label for="tuesday">Friday</label>
                        <input type="text" id="Friday_start" name="Friday_start" placeholder="starting time" />
                        <input type="text" id="Friday_end" name="Friday_end" placeholder="Ending time" />

                        <input type="checkbox" id="Saturday" name="availability_days[]" value="Saturday" />
                        <label for="Saturday">Saturday</label>
                        <input type="text" id="Saturday_start" name="Saturday_start" placeholder="starting time" />
                        <input type="text" id="Saturday_end" name="Saturday_end" placeholder="Ending time" />

                        Repeat for other days 
                    </div>-->
                    <div class="checkbox">
                        <input type="checkbox" id="monday" name="availability_days[]" value="Monday" />
                        <label for="monday">Monday</label>

                        <input type="checkbox" id="monday_morning" name="Monday_morning" value="Morning" />
                        <label for="monday_morning">Morning</label>

                        <input type="checkbox" id="monday_afternoon" name="Monday_afternoon" value="Afternoon" />
                        <label for="monday_afternoon">Afternoon</label>

                        <input type="checkbox" id="monday_evening" name="Monday_evening" value="Evening" />
                        <label for="monday_evening">Evening</label>
                    </div>

                    <div class="checkbox">
                        <input type="checkbox" id="tuesday" name="availability_days[]" value="Tuesday" />
                        <label for="tuesday">Tuesday</label>

                        <input type="checkbox" id="tuesday_morning" name="Tuesday_morning" value="Morning" />
                        <label for="tuesday_morning">Morning</label>

                        <input type="checkbox" id="tuesday_afternoon" name="Tuesday_afternoon" value="Afternoon" />
                        <label for="tuesday_afternoon">Afternoon</label>

                        <input type="checkbox" id="tuesday_evening" name="Tuesday_evening" value="Evening" />
                        <label for="tuesday_evening">Evening</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="wednesday" name="availability_days[]" value="Wednesday" />
                        <label for="wednesday">Wednesday</label>

                        <input type="checkbox" id="wednesday_morning" name="Wednesday_morning" value="Morning" />
                        <label for="wednesday_morning">Morning</label>

                        <input type="checkbox" id="wednesday_afternoon" name="Wednesday_afternoon" value="Afternoon" />
                        <label for="wednesday_afternoon">Afternoon</label>

                        <input type="checkbox" id="wednesday_evening" name="Wednesday_evening" value="Evening" />
                        <label for="wednesday_evening">Evening</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="Thursday" name="availability_days[]" value="Thursday" />
                        <label for="Thursday">Thursday</label>

                        <input type="checkbox" id="Thursday_morning" name="Thursday_morning" value="Morning" />
                        <label for="tuesday_morning">Morning</label>

                        <input type="checkbox" id="Thursday_afternoon" name="Thursday_afternoon" value="Afternoon" />
                        <label for="tuesday_afternoon">Afternoon</label>

                        <input type="checkbox" id="Thursday_evening" name="Thursday_evening" value="Evening" />
                        <label for="tuesday_evening">Evening</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="Friday" name="availability_days[]" value="Friday" />
                        <label for="Friday">Friday</label>

                        <input type="checkbox" id="Friday_morning" name="Friday_morning" value="Morning" />
                        <label for="Friday_morning">Morning</label>

                        <input type="checkbox" id="tuesday_afternoon" name="Friday_afternoon" value="Afternoon" />
                        <label for="Friday_afternoon">Afternoon</label>

                        <input type="checkbox" id="Friday_evening" name="Friday_evening" value="Evening" />
                        <label for="Friday_evening">Evening</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="Saturday" name="availability_days[]" value="Saturday" />
                        <label for="Saturday">Saturday</label>

                        <input type="checkbox" id="Saturday_morning" name="Saturday_morning" value="Morning" />
                        <label for="Saturday_morning">Morning</label>

                        <input type="checkbox" id="Saturday_afternoon" name="Saturday_afternoon" value="Afternoon" />
                        <label for="Saturday_afternoon">Afternoon</label>

                        <input type="checkbox" id="Saturday_evening" name="Saturday_evening" value="Evening" />
                        <label for="Saturday_evening">Evening</label>
                    </div>
                    <br /><br />
                    <center>
                        <input type="submit" class="btn btn-primary py-2 px-4 ms-3" name="timeslot" value="Submit" />
                    </center>
                </form>
            </div>
        </div>
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
    </div>

</body>

</html>