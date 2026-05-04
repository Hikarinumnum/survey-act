<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];
  $contact = $_POST['contact'];
  $barangay = $_POST['barangay'];
  $password = $_POST['password'];

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (fname, lname, email, contact, barangay, password)
          VALUES (?, ?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($conn, $sql);

  if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
  }

  mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $fname,
    $lname,
    $email,
    $contact,
    $barangay,
    $hashedPassword
  );

  if (mysqli_stmt_execute($stmt)) {
    // Go straight to login page after registering
    header("Location: index.html?status=goto_login");
    exit();
  } else {
    die("Execute failed: " . mysqli_error($conn));
  }
}