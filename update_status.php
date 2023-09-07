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
} else {
    echo "Invalid Request."; 
}
?>
