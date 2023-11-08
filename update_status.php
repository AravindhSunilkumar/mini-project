<?php
session_start();
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

if (isset($_POST['serviceId']) == "default") {
    if (isset($_POST['doctorId']) == "default") {
        if (isset($_POST['section'])) {
            if (isset($_POST['serviceId']) && isset($_POST['doctorId']) && isset($_POST['section'])  && isset($_POST['appointmentDate'])) {
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
                        $htmlContent = '';
                        // echo "Update successful<br>";
                        // Split the $timeInterval string into an array of time slots
                        $timeSlots = explode(', ', $timeInterval);


                        // Return the available time slots in HTML format
                        foreach ($timeSlots as $timeSlot) {
                            $checksql = "SELECT * FROM tbl_appointments WHERE doctor_id = '$doctorId' AND appointmentneed_date ='$appointmentDate' AND appo_time = '$timeSlot'";
                            $result2 = $conn->query($checksql);
                            if ($result2->num_rows > 0) {
                                echo "";
                            } else {
                                $htmlContent .= '<div class="col-3" style="width:207px;">';
                                $htmlContent .= '<input type="radio" class="btn btn-primary py-2 px-4 ms-3 time-slot-button" value="' . $timeSlot . '" style="height:81%;font-size:x-small" name="time" > <label for="" style="margin-left:2px;margin-top:7px;color:#fff;">' . $timeSlot . '</label>';
                                $htmlContent .= '</div>';
                            }
                        }

                        // Send the generated HTML content as the response
                        echo $htmlContent;
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
function userId($user)
{
    global $conn;
    $sql = "SELECT user_id FROM tbl_users WHERE user_username = '$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        $id = $row['user_id'];
    }
    return $id;
}
if ((isset($_POST['book_now']) && ($_SERVER['REQUEST_METHOD'] === 'POST'))) {
    if ($_POST['service_id'] != "default") {
        if ($_POST['doctor_id'] != "default") {
            if (($_POST['selectedTimeSlot']) !== 'undefined') { //

                // Send the response back to the JavaScript

                // Retrieve data from the POST request
                //$name = $_POST['name'];
                // $email = $_POST['email'];
                // $phoneNumber = $_POST['phoneNumber'];
                $serviceId = $_POST['service_id'];
                $_SESSION['s_id'] = $serviceId;
                $doctorId = $_POST['doctor_id'];
                $section = $_POST['section'];
                $appointmentDate = $_POST['appointmentDate'];
                $selectedTimeSlot = $_POST['selectedTimeSlot'];

                //echo $selectedTimeSlot . " " . $appointmentDate;
                $user = $_SESSION['name'];

                $userId = userId($user);

                $sql = "SELECT * FROM tbl_patient WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // If there are rows in the result, fetch and use the data here
                    while ($row = $result->fetch_assoc()) {
                        // Access data from the row
                        $patient_id = $row['patient_id'];
                        $patient_name = $row['full_name'];
                        $appo_sql = "SELECT * FROM tbl_appointments WHERE patient_id =? AND appointmentneed_date=?";
                        $stmt3 = $conn->prepare($appo_sql);
                        $stmt3->bind_param("is", $patient_id, $appointmentDate);
                        $stmt3->execute();
                        $result3 = $stmt3->get_result();
                        $uid = $_SESSION['id'];
                        $email = $_SESSION['email'];
                        if ($result3->num_rows > 0) {
                            echo "" . $patient_name . " is Already Book An Appointment on date " . $appointmentDate;
                            $stmt3->close();
                        } else {

                            $sqlcheck = "SELECT * FROM tbl_appointments WHERE user_id = '$uid' AND service_id = '$serviceId' ORDER BY created_at DESC LIMIT 1";
                            $result2 = mysqli_query($conn, $sqlcheck);
                            if (mysqli_num_rows($result2) > 0) {
                                $row = mysqli_fetch_assoc($result2);
                                $due_amount = $row['due_amount'];
                                $packid=$row['package_id'];
                                $sql = "INSERT INTO tbl_appointments (patient_id,user_id, doctor_id,patient_email, service_id,package_id,section,appo_time,due_amount, status, appointmentneed_date, created_at)
                                VALUES (?,?,?,?, ?,?,?,?,?, 'pending', ?, NOW())";
                                $stmt2 = $conn->prepare($sql);
                                $stmt2->bind_param("iiisssssss", $patient_id, $uid, $doctorId, $email, $serviceId,$packid, $section, $selectedTimeSlot, $due_amount, $appointmentDate);
                                $_SESSION['service_status'] = "notnew";
                                $_SESSION['package_id'] =$packid ;
                                // You may need to determine the patient_id based on the email or other criteria.
                                // For this example, I'm assuming you have a patients table with an email column.



                                $stmt2->execute();

                                //$result2 = $stmt2->get_result();


                                // Echo the success message directly
                                echo "Appointment booked successfully!";

                                //header("location: user-appointment.php");
                                unset($_POST['selectedTimeSlot']);
                                // Close the database connection
                                $stmt->close();
                                $stmt2->close();
                                $conn->close();
                            } else {
                                $sql = "INSERT INTO tbl_appointments (patient_id,user_id, doctor_id,patient_email, service_id,section,appo_time, status, appointmentneed_date, created_at)
                                VALUES (?,?,?,?, ?,?,?, 'pending', ?, NOW())";
                                $stmt2 = $conn->prepare($sql);
                                $stmt2->bind_param("iiisssss", $patient_id, $uid, $doctorId, $email, $serviceId, $section, $selectedTimeSlot, $appointmentDate);
                                $_SESSION['service_status'] = "new";
                                // You may need to determine the patient_id based on the email or other criteria.
                                // For this example, I'm assuming you have a patients table with an email column.



                                $stmt2->execute();

                                //$result2 = $stmt2->get_result();


                                // Echo the success message directly
                                echo "Appointment booked successfully!";

                                //header("location: user-appointment.php");
                                unset($_POST['selectedTimeSlot']);
                                // Close the database connection
                                $stmt->close();
                                $stmt2->close();
                                $conn->close();
                            }
                        }
                    }
                } else {
                    echo json_encode(["message" => "somthing went wrong"]);
                }
            } else {
                echo "Please choose a time";
            }
        } else {
            echo "Please choose a Doctor";
        }
    } else {
        echo "Please choose a service";
    }
    // Perform any necessary validation on the data here

}




// Prepare and execute the SQL INSERT query
