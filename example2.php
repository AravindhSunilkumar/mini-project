

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<!-- Button to trigger the SweetAlert -->
<button onclick="showPrompt()">Forgot Password?</button>
<!-- Display a SweetAlert when the link is clicked -->
<script>
    function showPrompt() {
        Swal.fire({
            title: 'Enter your email',
            input: 'email',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: (email) => {
                // You can add client-side validation here if needed
                return fetch('example3.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'email=' + email,
                })
                    .then(response => response.text())
                    .then(response => {
                        Swal.fire(response)
                    })
                    .catch(error => {
                        Swal.fire('Error', 'An error occurred while processing your request', 'error')
                    })
            }
        })
    }
</script>



</body>
</html>
