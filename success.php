<?php
include 'partials/_dbconnect.php';
require 'phpqrcode/qrlib.php';

// Fetch the email and event from the URL
$email = isset($_GET['email']) ? $_GET['email'] : '';
$event = isset($_GET['event']) ? $_GET['event'] : '';

if (!$email || !$event) {
    echo "Invalid request!";
    exit();
}

// Retrieve user details from the database
$query = "SELECT * FROM `register` WHERE email='$email' AND event='$event'";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    echo "User not found!";
    exit();
}
// QR Code File Path
$qrFile = "qrcodes/" . md5($email . $event) . ".png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            width: 90%; /* Adjust width */
            max-width: 350px; /* Set max width */
            text-align: center;
        }
        img {
            max-width: 220px; /* Reduce QR code size */
            height: auto;
            margin: 10px 0;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column justify-content-center align-items-center vh-100">
    <div class="card p-3 shadow">
        <h4 class="mb-3">Registration Successful 🎉</h4>
        <p>Thank you for registering, <b><?php echo $user['name']; ?></b>!</p>
        <p>Your event: <b><?php echo $user['event']; ?></b></p>
        <p>Scan or download your QR Code:</p>
        <img src="<?php echo $qrFile; ?>" alt="QR Code" class="mx-auto d-block">
        <br>
        <a href="<?php echo $qrFile; ?>" download class="btn btn-primary btn-sm mt-3">Download QR Code</a>
        <br>
        <a href="index.html" class="btn btn-secondary btn-sm mt-2">Go to Home</a>
    </div>
</body>
</html>

