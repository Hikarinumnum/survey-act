<?php
include 'connect.php';

// (This part will NOT run yet because of exit above)
// name and email from form
$name = $_POST['name'];
$email = $_POST['email'];

// insert into database
$sql = "INSERT INTO Users (name, email) VALUES ('$name', '$email')";

if (mysqli_query($conn, $sql)) {
    echo "Data inserted successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>