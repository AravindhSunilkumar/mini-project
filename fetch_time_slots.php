<?php
include('connection.php');


if ($_GET["date"] && $_GET["doctor_id"]) {
    $selectedDate = $_GET["date"];
    $selectedDoctorId = $_GET["doctor_id"];

    // Add your logic to query the database and fetch available time slots based on the selectedDate and selectedDoctorId
    // Replace this with your actual database query

    // Example: Fetch time slots from the tbl_doctortime table
    $stmt = $conn->prepare("SELECT A_start_time, A_end_time FROM tbl_doctortime WHERE doctor_id = ? ");
    $stmt->bind_param("is", $selectedDoctorId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and format the available time slots
    $availableTimeSlots = [];
    while ($row = $result->fetch_assoc()) {
        $startTime = strtotime($row["A_start_time"]);
        $endTime = strtotime($row["A_end_time"]);

        // Generate time slots with a 15-minute interval
        while ($startTime < $endTime) {
            $timeSlot = date("h:i A", $startTime);
            $availableTimeSlots[] = $timeSlot;
            $startTime += 900; // 15 minutes in seconds
        }
    }

    // Return the available time slots in HTML format
    foreach ($availableTimeSlots as $timeSlot) {
        echo '<div class="col">';
        echo '<a href="" class="btn btn-primary py-2 px-4 ms-3">' . $timeSlot . '</a>';
        echo '</div>';
    }
}
?>
