<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic Patient History</title>
</head>
<body>
    <h1>Dental Clinic Patient History</h1>

    <?php
    include('connection.php');
    

    // Fetch patient history details from the database
    $query = "SELECT * FROM tbl_appointments";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Appointment ID</th><th>Patient Email</th><th>Doctor ID</th><th>Service ID</th><th>Section</th><th>Appointment Time</th><th>Status</th><th>Appointment Date</th><th>Created At</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["appointment_id"] . "</td>";
            echo "<td>" . $row["patient_email"] . "</td>";
            echo "<td>" . $row["doctor_id"] . "</td>";
            echo "<td>" . $row["service_id"] . "</td>";
            echo "<td>" . $row["section"] . "</td>";
            echo "<td>" . $row["appo_time"] . "</td>";
            echo "<td>" . $row["status"] . "</td>";
            echo "<td>" . $row["appointmentneed_date"] . "</td>";
            echo "<td>" . $row["created_at"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No patient history records found.";
    }

    // Close the database connection
    $conn->close();
    ?>

</body>
</html>
