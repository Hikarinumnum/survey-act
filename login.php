<?php
session_start();
include 'connect.php';

// Changed: was $_POST['username'], now matches the HTML form field name="email"
 $email = $_POST['email'] ?? '';
 $password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: index.html?status=login_error&msg=" . urlencode("Please enter your email and password."));
    exit();
}

 $sql = "SELECT * FROM users WHERE email = ? OR contact = ?";
 $stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $email, $email);
mysqli_stmt_execute($stmt);

 $result = mysqli_stmt_get_result($stmt);
 $user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fname']   = $user['fname'];

    // Redirect back with URL params — the HTML reads these on load
    header("Location: index.html?status=login_success&name=" . urlencode($user['fname']) . "&id=" . urlencode($user['id']));
    exit();
} else {
    header("Location: index.html?status=login_error&msg=" . urlencode("Invalid email or password."));
    exit();
}
?>