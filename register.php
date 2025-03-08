<?php
include 'partials/_dbconnect.php';
require 'phpqrcode/qrlib.php'; // Include QR Code Library

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $college = $_POST['college'];
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $email = $_POST['email'];
    $phno = $_POST['phno'];
    $event = $_POST['event'];
    $transaction = $_POST['transaction'];

    // Check if user is already registered for the event
    $existsSql = "SELECT * FROM `register` WHERE email = '$email' AND event = '$event'";
    $result = mysqli_query($con, $existsSql);
    
    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('Already registered!'); window.location.href='register.php';</script>";
        exit();
    }

    // Insert user data
    $sql = "INSERT INTO `register` (`name`, `college`, `branch`, `year`, `email`, `phno`, `event`, `tid`) 
            VALUES ('$name', '$college', '$branch', '$year', '$email', '$phno', '$event', '$transaction')";

    if (mysqli_query($con, $sql)) {
        // Generate Unique QR Code
        $qrData = "Name: $name\nEmail: $email\nEvent: $event\nTransaction ID: $transaction";
        $qrFile = "qrcodes/" . md5($email . $event) . ".png"; // Unique QR file name
        
        // Check if directory exists
        if (!is_dir("qrcodes")) {
            mkdir("qrcodes", 0777, true);
        }

        QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 10);

        // Redirect to success page
        header("Location: success.php?email=" . urlencode($email) . "&event=" . urlencode($event));
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <href src="style.css"></href> -->
    <style>
        .text-2xl font-bold text-center mb-4 {
            display: flexbox;
            justify-content: center;
            flex-wrap: wrap;
            float: left;
        }

        .img {
            width: auto;
            float: right;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen py-10"
    style="background-image: url('assets/images/bg3.jpeg'); background-size: cover; background-position: center;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md mt-2 mb-2">
        <h2 class="text-2xl font-bold text-center mb-4">Event Registration</h2>
        <form name="registerForm" action="register.php" method="POST" class="space-y-4" onsubmit="return validateForm();">
            <div>
                <label class="block font-medium">Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block font-medium">College</label>
                <input type="text" name="college" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block font-medium">Branch</label>
                <input type="text" name="branch" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block font-medium">Year</label>
                <input type="text" name="year" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block font-medium">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block font-medium">Phone No.</label>
                <input type="tel" name="phno" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block font-medium">Events Interested</label>
                <select name="event" class="w-full p-2 border rounded" required>
                    <option value="" disabled selected>Select an Event</option>
                    <option value="coding">coding</option>
                    <option value="hackathon">hackathon</option>
                    <option value="robo">robo</option>
                    <option value="music">music</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">Payment QR (75 rs.)</label>
                <div style="display: flex;">
                    <img src="assets/images/qr.png" alt="qr" width="120px" height="120px">
                </div>
            </div>
            <div>
                <label class="block font-medium">Transaction ID</label>
                <input type="text" name="transaction" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Register</button>
        </form>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function validateForm() {
            let name = document.forms["registerForm"]["name"].value;
            let college = document.forms["registerForm"]["college"].value;
            let branch = document.forms["registerForm"]["branch"].value;
            let year = document.forms["registerForm"]["year"].value;
            let email = document.forms["registerForm"]["email"].value;
            let phno = document.forms["registerForm"]["phno"].value;
            let event = document.forms["registerForm"]["event"].value;
            let transaction = document.forms["registerForm"]["transaction"].value;
            let namePattern = /^[A-Za-z\s]+$/;
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            let phonePattern = /^[0-9]{10}$/;
            let yearPattern = /^[1-4]$/;

            if (!namePattern.test(name)) {
                alert("Name must contain only letters and spaces.");
                return false;
            }
            if (college.trim() == "") {
                alert("College name cannot be empty.");
                return false;
            }
            if (branch.trim() == "") {
                alert("Branch cannot be empty.");
                return false;
            }
            if (!yearPattern.test(year)) {
                alert("Year must be between 1 and 4.");
                return false;
            }
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            if (!phonePattern.test(phno)) {
                alert("Phone number must be exactly 10 digits.");
                return false;
            }
            if (event == "") {
                alert("Please select an event.");
                return false;
            }
            if (transaction.trim() == "") {
                alert("Transaction ID cannot be empty.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>