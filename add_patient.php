<?php
include('connection.php');
$patient = isset($_GET['patient']) ? intval($_GET['patient']) : 0;
if (isset($_POST['insert'])) {
    $name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];
    $allergyinfo = empty($_POST['allergy_info']) ? NULL : $_POST['allergy_info'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    if ((strlen($phone) > 10 || strlen($phone) < 10)) {
        echo '<script>alert("Please enter exact 10 digit phone  number ");</script>';
    } else {
        $email=$_POST['Email'];
        $check_sql = "SELECT * FROM tbl_users WHERE user_email = '$email'";
        $check_result = $conn->query($check_sql);
        if ($check_result->num_rows > 0) {
            echo '<script>alert("A patient with the same Email already exists.");window.location.href = "add_patient.php";</script>';
        } else {

            if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == UPLOAD_ERR_OK) {
                $targetDir = "img/patients/";
                $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]);
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Generate a unique filename
                $newFilename = uniqid() . '.' . $fileType;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetDir . $newFilename)) {
                    // File was successfully uploaded
                    // Now insert the file address into the table
                    $fileAddress = $targetDir . $newFilename;
                    if (isset($name) && isset($gender) && isset($dob)  && isset($address) && isset($fileAddress)  && isset($phone)) {
                        // Process phone number to remove non-numeric characters
                        $phone = preg_replace('/\D/', '', $phone);
                        // Insert data into the table
                        $sql = "INSERT INTO tbl_patient (full_name, gender, date_of_birth, address, profile_picture, allergy_info, emergency_contact_phone) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssssss", $name, $gender, $dob, $address, $fileAddress, $allergyinfo, $phone);



                        if ($stmt->execute()) {
                            //doctor login username and password 
                            
                               
                                $dsql = "INSERT INTO tbl_users (user_username,user_email,user_password) VALUES('$name','$email','$name')";

                                if ($conn->query($dsql) === true) {
                                    // Display success message or perform any other desired actions
                                    echo '<script>
                                        var confirmed = confirm("Login Password set successfully.");
                                        if (confirmed) {
                                            window.location.href = "doctors_list.php";
                                        }
                                        </script>';
                                }
                            
                            echo '<script>
            var confirmed = confirm("Patient added successfully. Click OK to continue.");
            if (confirmed) {
                window.location.href = "patients_list.php";
            }
        </script>';
                        } else {
                            echo "Error inserting data: " . $stmt->error;
                        }


                        $stmt->close();
                        $conn->close();
                    } else {
                        echo "<script>alert(Please fill all fields!  )</script>";
                    }
                } else {
                    echo "<script>alert(Error moving file to target directory.)</script>";
                }
            } else {
                echo "Error uploading file.";
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        function toggleAllergyInfo(value) {
            var allergyInput = document.getElementById('allergy_input');

            if (value === 'yes') {
                allergyInput.style.display = 'block';
            } else {
                allergyInput.style.display = 'none';
            }
        }
    </script>
</head>


<body class="add-patients">

    <?php include('admin_menu.php'); ?>
    <section class="back-img">
        <div class="form-container ">
            <div class="center-header  justify-content-center">
                <center>
                    <h2>Patient Registration Form</h2>
                </center>
            </div>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                <div class="flex">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required><br><br>
                </div> 
                <div class="flex">
                    <label for="Email">Email:</label>
                    <input type="email" id="Email" name="Email" required><br><br>
                </div>
                <div class="flex">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select><br><br>
                </div>

                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required><br><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" size="50"><br><br>

                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture"><br><br>

                <label>Allergy Info:</label>
                <input type="button" id="yes" value="Yes" onclick="toggleAllergyInfo('yes')">
                <input type="button" id="no" value="No" onclick="toggleAllergyInfo('no')"><br><br>

                <div id="allergy_input" style="display: none;">
                    <label for="allergy_info">Type Allergy Info:</label>
                    <input type="text" id="allergy_info" name="allergy_info" size="50"><br><br>
                </div>



                <label for="emergency_contact_phone">Emergency Contact Phone:</label>
                <input type="text" id="emergency_contact_phone" name="phone" oninput="checkPhoneNumber()">
                <span id="phoneMessage" style="color: red;"></span><br><br>

                <div id="phoneAlert" class="alert  alert-warning alert-dismissible fade show" role="alert" style="display: none;">
                    <strong>Phone number</strong> should only contain 10 digits.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>



                <input type="submit" name="insert" value="Add Patient ">

            </form>

        </div>


    </section>
    <script>
        function checkPhoneNumber() {
            const phoneNumberInput = document.getElementById('emergency_contact_phone');
            const phoneMessage = document.getElementById('phoneMessage');
            const phoneAlert = document.getElementById('phoneAlert');
            const phoneNumber = phoneNumberInput.value.replace(/\D/g, '');
            if (phoneNumberInput.value.length > 10 || phoneNumberInput.value.length < 10) {
                phoneMessage.textContent = '';
                phoneAlert.style.display = 'block';
            } else {
                phoneMessage.textContent = '';
                phoneAlert.style.display = 'none';
            }
        }


        const loginText = document.querySelector(".title-text .login");
        const loginForm = document.querySelector("form.login");
        const loginBtn = document.querySelector("label.login");
        const signupBtn = document.querySelector("label.signup");
        const signupLink = document.querySelector("form .signup-link a");
        signupBtn.onclick = (() => {
            loginForm.style.marginLeft = "-50%";
            loginText.style.marginLeft = "-50%";
        });
        loginBtn.onclick = (() => {
            loginForm.style.marginLeft = "0%";
            loginText.style.marginLeft = "0%";
        });
        signupLink.onclick = (() => {
            signupBtn.click();
            return false;
        });
    </script>
</body>


</html>