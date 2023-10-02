<?php
include("connection.php"); // Include your database connection


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
}
function timeFetch($s, $e)
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
if (isset($_POST['serviceId']) && isset($_POST['doctorId']) && isset($_POST['section'])  && isset($_POST['appointmentDate'])) {
    if (isset($_POST['serviceId']) == "default") {
        if (isset($_POST['doctorId']) == "default") {
            if (isset($_POST['section'])) {
                $serviceId = $_POST['serviceId'];
                $doctorId = $_POST['doctorId'];

                $section = $_POST['section'];
                $appointmentDate = $_POST['appointmentDate'];
                $timestamp = strtotime($appointmentDate);

                if ($timestamp === false) {
                    echo "";
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
                    $statusValue = "active";
                    $statusvalue = "Active";
                    if ($section == "morning") {
                        $timeInterval = timeFetch("9:00AM", "12:00PM");
                        $update_sql = "SELECT * FROM tbl_doctortime  
                       WHERE doctor_id = '$doctorId' AND slot_id = '$dayNum' AND status = '$statusvalue' AND morning = '$statusValue '";
                    } else if ($section == "afternoon") {
                        $timeInterval = timeFetch("12:00PM", "2:50PM");

                        $update_sql = "SELECT * FROM tbl_doctortime  
                       WHERE doctor_id = '$doctorId' AND slot_id = '$dayNum' AND status = '$statusvalue' AND afternoon = '$statusValue '";
                    } else if ($section == "evening") {
                        $timeInterval = timeFetch("4:20PM", "5:00PM");
                        $update_sql = "SELECT * FROM tbl_doctortime  
                       WHERE doctor_id = '$doctorId' AND slot_id = '$dayNum' AND status = '$statusvalue' AND evening = '$statusValue '";
                    }

                    $result = $conn->query($update_sql);





                    // Check if any data is fetched
                    if ($result->num_rows > 0) {

                        // echo "Update successful<br>";
                        // Split the $timeInterval string into an array of time slots
                        $timeSlots = explode(', ', $timeInterval);


                        // Return the available time slots in HTML format
                        foreach ($timeSlots as $timeSlot) {
                            //$response = "<p>Available time slots for $section on $appointmentDate: for $serviceId</p>";

                            $response = '<div class="col-3">';

                            $response .= '<button type="button" class="btn btn-primary py-2 px-4 ms-3 time-slot-button" data-time-slot="' . $timeSlot . '" style="width:137px;height:81%;font-size:x-small">' . $timeSlot . '</button>';
                            $response .= '</div>';
                            echo $response;
                        }
                    } else {

                        $response = '<div style="color:red;" class="col-3">';
                        $response .= "<p >No Consulting</p>";
                        $response .= '</div>';
                        echo $response;
                    }
                }
                $timestamp = $appointmentDate = $section = $doctorId = $serviceId = "";
            } else {
                $response .= "<script>alert(Please choose your needed time)</script>";
                echo $response;
            }
        } else {
            echo "<script>alert(Please choose a doctor)</script>";
        }
    } else {
        echo "<script>alert(Please choose a service)</script>";
    }
}




// Process the data and fetch corresponding information
// You can replace this with your actual database queries and data formatting

// Example response (replace with your data)
// $response = "<p>Available time slots for $section on $appointmentDate:for $serviceId</p>";
// $response .= "<ul>";
//$response .= "<li>9:00 AM - 10:00 AM</li>";
// $response .= "<li>10:30 AM - 11:30 AM</li>";
// Add more time slots here based on your data
//$response .= "</ul>";

// Send the response back to the JavaScript

?>
<style>
    .selected-time-slot {
        background-color: #ff0000;
        /* Change to your desired color */
        color: #ffffff;
        /* Change text color if needed */
    }
</style>
<script>
    // Get all buttons with the class 'time-slot-button'
    const buttons = document.querySelectorAll('.time-slot-button');

    // Add a click event listener to each button
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            // Reset the color of all buttons to the default color
            buttons.forEach(btn => {
                btn.classList.remove('selected-time-slot');
            });

            // Change the color of the clicked button
            button.classList.add('selected-time-slot');
        });
    });
</script>