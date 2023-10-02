    <?php
    include('connection.php');
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
    // Get posted data
    $serviceId = $_POST['serviceId'];
    $doctorId = $_POST['doctorId'];
    $section = $_POST['section'];
    $appointmentDate = $_POST['appointmentDate'];
    $timestamp = strtotime($appointmentDate);

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
        if ($section == "morning") {
            $timeInterval = timeFetch("9:00AM", "12:00PM");
            $update_sql = "SELECT * FROM tbl_doctortime  
                           WHERE doctor_id = ? AND slot_id = ? AND status = ? AND morning = ?";
            $statusValue = "Active"; // Store the status value in a variable
        } else if ($section == "afternoon") {
            $timeInterval = timeFetch("12:00PM", "2:50PM");
            $update_sql = "SELECT * FROM tbl_doctortime  
                           WHERE doctor_id = ? AND slot_id = ? AND status = ? AND afternoon = ?";
            $statusValue = "Active"; // Store the status value in a variable
        } else if ($section == "evening") {
            $timeInterval = timeFetch("4:20PM", "5:00PM");
            $update_sql = "SELECT * FROM tbl_doctortime  
                           WHERE doctor_id = ? AND slot_id = ? AND status = ? AND evening = ?";
            $statusValue = "Active"; // Store the status value in a variable
        }
        
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssss", $doctorId, $dayNum, $statusValue, $statusValue);
        
        if ($stmt->execute()) {
           // echo "Update successful<br>";
           // Split the $timeInterval string into an array of time slots
        $timeSlots = explode(', ', $timeInterval);
        
        // Return the available time slots in HTML format
        foreach ($timeSlots as $timeSlot) {
            //$response = "<p>Available time slots for $section on $appointmentDate: for $serviceId</p>";
            
            $response = '<div class="col-3">';
            $response .= '<a href="" class="btn btn-primary py-2 px-4 ms-3" style="width:137px;height:81%;font-size:x-small">' . $timeSlot . '</a>';
            $response .= '</div>';
           
            echo $response;
        }
        } else {
            $response ='<div class="col-3">';
            $response .="<p >No Consulting</p>";
            $response .= '</div>';

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
