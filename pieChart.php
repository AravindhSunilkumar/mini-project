<?php

include("connection.php");

// Fetch service names from tbl_services
$sqlServices = "SELECT service_name FROM tbl_services";
$resultServices = $conn->query($sqlServices);

$labels = [];
while ($rowService = $resultServices->fetch_assoc()) {
    $labels[] = $rowService['service_name'];
}

// Fetch the count of appointments for each service from tbl_appointments
$sqlAppointments = "SELECT s.service_name, COUNT(a.service_id) as count 
                    FROM tbl_services s
                    LEFT JOIN tbl_appointments a ON s.service_id = a.service_id
                    GROUP BY s.service_id";
$resultAppointments = $conn->query($sqlAppointments);

$data = [];
while ($rowAppointment = $resultAppointments->fetch_assoc()) {
    $data[] = $rowAppointment['count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div>
    <div>
    <canvas id="myPieChart" width="745" height="400" style="display: block; box-sizing: border-box; height: 400px; width: 745px;"></canvas>
    

    <script>
        const data = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                data: <?php echo json_encode($data); ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', 'red'],
                hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50','red']
            }]
        };

        const ctx = document.getElementById('myPieChart').getContext('2d');

        const myPieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
    </div>
    <div style="position: relative;margin-top: -443px;width: 100px;margin-left: 61%;">
    <img src="img/pie.png" alt="" style="width: 433px;margin-left: 33px;">
    </div>
    </div>
    
</body>
</html>
