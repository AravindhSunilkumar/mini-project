
<?php
include('connection.php');
function fetchTableData($conn, $tableName)
{
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}
$feedbacks = fetchTableData($conn, "tbl_feedbacks");
//update question and answer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_question"])) {
    $feedback_id = $_POST["feedback_id"];
    $status = $_POST["status"];
   

    // Update the service status in the database
    $update_sql = "UPDATE tbl_feedbacks SET status = ? WHERE feedback_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $feedback_id);

    if ($stmt->execute()) {
        // Status updated successfully
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
}
//delete question 
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['feedback_id'])) {
    $feedback_id = $_GET['feedback_id'];




    // Delete the doctor from the database
    $delete_sql = "DELETE FROM tbl_feedbacks WHERE feedback_id = '$feedback_id'";
    if ($conn->query($delete_sql) === TRUE) {
        // Deletion successful
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Deletion failed
        echo "Error deleting record: " . $conn->error;
    }
}

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include('admin_menu.php'); ?>
    <div class="d-flex justify-content-center" style="margin: 20px;">
        <div class="table-responsive">
            <h2 class="text-center">User's Questions</h2>
            <table class="col-* table table-success table-striped shadow-lg">
                <thead>
                    <tr>
                        <th>Feedback Id </th>
                        <th>Patient Name </th>
                        <th>Feedback</th>
                        <th>Status</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $index => $feedback) : ?>
                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                            <td><?= $feedback['feedback_id']; ?></td>
                            <td><?= $feedback['patient_name']; ?></td>
                            <td><?= $feedback['feedback']; ?></td>
                            <td><?= $feedback['status']; ?></td>
                           
                            
                            <div class="d-flex">
                                <!-- ... your existing table rows ... -->
                                <td class="wrapper">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-info" onclick="showEditForm(
                                                <?= $feedback['feedback_id']; ?>,
                                                '<?= $feedback['status']; ?>'
                                                                            
                                            )">Edit</a>

                                    <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&feedback_id=<?= $feedback['feedback_id']; ?>" class="btn btn-danger">Del</a>
                                </td>

                            </div>

                        <?php endforeach; ?>
            </table>
        </div>
    </div>
    <!-- Modal for editing doctor details -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- JavaScript to handle edit form display and submission -->
                    <script>
                        function showEditForm(feedbackid, status) {
                            var modal = document.getElementById("editModal");
                            var modalBody = modal.querySelector(".modal-body");

                            var form = `
                                        <form action="" method="post" class="text-center">
                                            <input type="hidden" name="feedback_id" value="${feedbackid}">
                                            <label for="status">Select Status:</label>
                                            <select id="status" name="status">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select><br>
                                            <button type="submit" name="update_question" class="btn btn-success">Update</button>
                                        </form>
                                    `;


                            modalBody.innerHTML = form;
                            $(modal).modal("show");

                            // Show a confirmation message after updating
                            var updateButton = modal.querySelector("[name='update_service']");
                            updateButton.addEventListener("click", function() {
                                alert("service information updated successfully!");
                            });
                        }
                    </script>

                </div>
            </div>
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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>