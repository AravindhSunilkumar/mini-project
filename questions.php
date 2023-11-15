<?php
include('connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_QA"])) {

    $question = $_POST["question"];
    $answer = $_POST["answer"];
    $sql = "insert into tbl_prebuild_questions (question,answer) values('$question','$answer')";
    $result = $conn->query($sql);
    header("Location:questions.php"); // Replace "success.php" with the actual success page
    exit();
}
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
$questions = fetchTableData($conn, "tbl_prebuild_questions");
//update question and answer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_question"])) {
    $question_id = $_POST["question_id"];
    $question = $_POST["question"];
    $answer = $_POST["answer"];

    // Update the service status in the database
    $update_sql = "UPDATE tbl_prebuild_questions SET question = ?  , answer = ? WHERE prequestion_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $question, $answer, $question_id);

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
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];




    // Delete the doctor from the database
    $delete_sql = "DELETE FROM tbl_prebuild_questions WHERE prequestion_id = '$question_id'";
    if ($conn->query($delete_sql) === TRUE) {
        // Deletion successful
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Deletion failed
        echo "Error deleting record: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/services.css">
    <title>Document</title>


<body>
    <?php include('admin_menu.php'); ?>
    <div class="d-flex justify-content-center" style="margin: 20px;">

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <div class="row">
                <h2 class="text-uppercase text-center">add new Q/A</h2>
            </div>
            <div class="row" style="margin: 10px;">
                <label for="question" class="text-center ">Enter Question</label>
                <input type="text" name="question" id="question" placeholder="Question" required>
            </div>
            <div class="row" style="margin: 10px;">
                <label for="answer" class="text-center">Enter Answer</label>
                <input type="text" name="answer" id="" placeholder="Answer" required>
            </div>
            <div class="row" style="margin: 10px;">
                <input type="submit" value="Add Q/A" name="add_QA">
            </div>
        </form>
    </div>
    <div class="d-flex justify-content-center" style="margin: 20px;">
        <div class="table-responsive">
            <h2 class="text-center">Build-in Questions And Answers</h2>
            <table class="col-* table table-success table-striped shadow-lg">
                <thead>
                    <tr>
                        <th>Question Id </th>
                        <th>Questions </th>
                        <th>Answers</th>
                        <th>Status </th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $index => $question) : ?>
                        <tr class="table-row <?= $index % 2 === 0 ? 'even' : 'odd'; ?>">
                            <td><?= $question['prequestion_id']; ?></td>
                            <td><?= $question['question']; ?></td>
                            <td><?= $question['answer']; ?></td>






                            <td>
                                <div class="onoffswitch">
                                    <input type="checkbox" class="onoffswitch-checkbox" id="serviceSwitch<?= $question['prequestion_id']; ?>" <?= $question['status'] === 'Active' ? 'checked' : ''; ?>>
                                    <label class="onoffswitch-label swi" for="serviceSwitch<?= $question['prequestion_id']; ?>" onclick="toggleServiceStatus(<?= $question['prequestion_id']; ?>, '<?= $question['status']; ?>')">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </td>
                            <!-- JavaScript to handle status toggle -->
                            <script>
                                function toggleServiceStatus(questionId, currentStatus) {
                                    var newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';

                                    var confirmation = confirm("Are you sure you want to change the status to " + newStatus + "?");
                                    if (confirmation) {
                                        // Send an AJAX request to update the status
                                        $.ajax({
                                            type: "POST",
                                            url: "update_status.php", // Replace with the actual PHP script that updates the status
                                            data: {
                                                question_id: questionId,
                                                new_status: newStatus
                                            },
                                            success: function(response) {
                                                if (response === 'success') {
                                                    // Status updated successfully
                                                    location.reload(); // Reload the page or update the status in the table dynamically
                                                } else {
                                                    alert("Error updating status: " + response);
                                                }
                                            }
                                        });
                                    } else {
                                        location.reload();
                                    }
                                }
                            </script>





                            <div class="d-flex">
                                <!-- ... your existing table rows ... -->
                                <td class="wrapper">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-info" onclick="showEditForm(
                                                <?= $question['prequestion_id']; ?>,
                                                '<?= $question['question']; ?>',
                                                '<?= $question['answer']; ?>'
                                                                            
                                            )">Edit</a>

                                    <a href="<?= $_SERVER["PHP_SELF"] ?>?action=delete&question_id=<?= $question['prequestion_id']; ?>" class="btn btn-danger">Del</a>
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
                        function showEditForm(questionId, question, answer) {
                            var modal = document.getElementById("editModal");
                            var modalBody = modal.querySelector(".modal-body");

                            var form = `
                                        <form action="" method="post" class="text-center">
                                            <input type="hidden" name="question_id" value="${questionId}">
                                            <label>Question:</label><br>
                                            <textarea name="question" required>${question}</textarea><br>
                                            <label>Answer:</label><br>
                                            <textarea name="answer" required>${answer}</textarea><br>
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

</body>

</html>