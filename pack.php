<?php
session_start();
include("connection.php");

function patientid($user_id)
{
    global $conn;
    $sql = "SELECT * FROM tbl_patient WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        return $row['patient_id'];
    }
    return null;
}

$user_id = $_SESSION['id'];
$patient_id = patientid($user_id);
$service_id = $_SESSION['s_id'];

if ($patient_id) {
    $sqlcheck = "SELECT * FROM tbl_appointments WHERE patient_id='$patient_id' AND service_id='$service_id'";
    $result2 = mysqli_query($conn, $sqlcheck);
    $row = mysqli_fetch_assoc($result2);

    if (mysqli_num_rows($result2) > 0) {
        $due_amount = $row['due_amount'];
        echo "Due Amount: " . $due_amount . "<br>";
        echo "Service ID: " . $service_id;
    } else {
        header('Location: package.php');
    }
} else {
    echo "Patient ID not found.";
}
?>
    