<?php
include("connection.php"); // Include your database connection
function timeFetch($s,$e)
{
    // Define the start and end times
    $start = strtotime($s);
    $end = strtotime($e);

    // Initialize an empty array to store the time intervals
    $timeIntervals = [];

    // Loop to generate time intervals
    while ($start < $end) {
        // Calculate the end time of the interval (15 minutes later)
        $intervalEnd = strtotime('+15 minutes', $start);

        // Format the times in AM/PM format
        $formattedStart = date('g:iA', $start);
        $formattedEnd = date('g:iA', $intervalEnd);

        // Create the interval string and add it to the array
        $timeIntervalString = $formattedStart . '-' . $formattedEnd;
        $timeIntervals[] = $timeIntervalString;

        // Move the start time to the next interval
        $start = $intervalEnd;
    }

    // Join the time intervals into a comma-separated string
    $timeIntervalsString = implode(', ', $timeIntervals);
    return $timeIntervalsString;
}

if (isset($_POST['service_id']) && isset($_POST['new_status'])) {
    $service_id = $_POST['service_id'];
    $new_status = $_POST['new_status'];

    // Update the service status in the tbl_services table
    $update_services_sql = "UPDATE tbl_services SET status = ? WHERE service_id = ?";
    $stmt_services = $conn->prepare($update_services_sql);
    $stmt_services->bind_param("si", $new_status, $service_id);

    // Update the status in the tbl_doctortime table based on service_id
    $update_doctortime_sql = "UPDATE tbl_doctortime AS dt
                              INNER JOIN tbl_services AS s ON dt.service_id = s.service_id
                              SET dt.status = ? 
                              WHERE s.service_id = ?";
    $stmt_doctortime = $conn->prepare($update_doctortime_sql);
    $stmt_doctortime->bind_param("si", $new_status, $service_id);

    // Execute both queries within a transaction
    $conn->begin_transaction();

    try {
        if ($stmt_services->execute() && $stmt_doctortime->execute()) {
            $conn->commit();
            echo 'success'; // Send a success response to the AJAX request
        } else {
            throw new Exception("Error updating status.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating status: " . $e->getMessage();
    } finally {
        $stmt_services->close();
        $stmt_doctortime->close();
        $conn->close();
    }
} elseif (isset($_POST['doctor_id']) && isset($_POST['new_status'])) {
    $doctor_id = $_POST['doctor_id'];
    $new_status = $_POST['new_status'];

    // Update the service status in the tbl_services table
    $update_doctor_sql = "UPDATE tbl_doctors SET status = ? WHERE doctor_id = ?";
    $stmt_doctor = $conn->prepare($update_doctor_sql);
    $stmt_doctor->bind_param("si", $new_status, $doctor_id);

    // Update the status in the tbl_doctortime table based on service_id
    $update_patient_sql = "UPDATE tbl_appointments AS dt
                              INNER JOIN tbl_doctors AS s ON dt.doctor_id = s.doctor_id
                              SET dt.status = ? 
                              WHERE s.doctor_id = ?";
    $stmt_patient = $conn->prepare($update_patient_sql);
    $stmt_patient->bind_param("si", $new_status, $doctor_id);

    // Execute both queries within a transaction
    $conn->begin_transaction();

    try {
        if ($stmt_doctor->execute() && $stmt_patient->execute()) {
            $conn->commit();
            echo 'success'; // Send a success response to the AJAX request
        } else {
            throw new Exception("Error updating status.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating status: " . $e->getMessage();
    } finally {
        $stmt_doctor->close();
        $stmt_patient->close();
        $conn->close();
    }
} else {
    echo "Invalid Request.";
}
if (isset($_POST['selectedDate']) && isset($_POST['selectedDoctorId'])) {
    // Handle the form submission
    $selectedDate = $_POST["selectedDate"];
    $selectedDoctorId = $_POST["selectedDoctorId"];
    $selectedSection = $_POST["section"];
    // Convert the date string to a timestamp
    $timestamp = strtotime($selectedDate);

    if ($timestamp === false) {
        echo "Invalid date format";
    } else {
        // Use the date() function to get the day of the week (0 = Sunday, 1 = Monday, ...)
        $dayOfWeek = date("w", $timestamp);

        // Define an array to map day of the week numbers to their names
        $daysOfWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

        // Get the day name based on the day of the week
        $dayName = $daysOfWeek[$dayOfWeek];
        if ($dayName == "Sunday") {
            $dayNum = "0";
        } elseif ($dayName == "Monday") {
            $dayNum = "1";
        } elseif ($dayName == "Tuesday") {
            $dayNum = "2";
        } elseif ($dayName == "Wednesday") {
            $dayNum = "3";
        } elseif ($dayName == "Thursday") {
            $dayNum = "4";
        } elseif ($dayName == "Friday") {
            $dayNum = "5";
        } elseif ($dayName == "Saturday") {
            $dayNum = "6";
        } else {
            echo "error:";
        }
        $update_sql = "SELECT * FROM tbl_doctortime  
                   WHERE doctor_id = ? AND slot_id = ? AND status = ?";

        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sss", $selectedDate, $dayNum, "Active");

        $conn->begin_transaction();
        if ($selectedSection == "morning") {
            $timeInterval = timeFetch("9:00AM", "12:00PM");
        }else if($selectedSection == "afternoon") {
            $timeInterval = timeFetch("12:00PM", "2:50PM");
        }else if($selectedSection == "evening"){
            $timeInterval = timeFetch("4:20PM", "5:00PM");
        }

        try {
            if ($stmt_services->execute() && $stmt_doctortime->execute()) {
                $conn->commit();
                echo 'success'; // Send a success response to the AJAX request
            } else {
                throw new Exception("Error updating status.");
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error updating status: " . $e->getMessage();
        } finally {
            $stmt_services->close();
            $stmt_doctortime->close();
            $conn->close();
        }
        // Return the available time slots in HTML format
        foreach ($timeInterval as $timeSlot) {
        echo '<div class="col">';
        echo '<a href="" class="btn btn-primary py-2 px-4 ms-3">' . $timeSlot . '</a>';
        echo '</div>';
    }
    }


   
}
