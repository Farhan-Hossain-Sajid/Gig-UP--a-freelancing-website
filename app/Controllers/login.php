<?php
session_start();


// 1) Connect
$conn = new mysqli('localhost','root','','freelance_db');
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}


// 2) Grab inputs
$email = $_POST['email'];
$pass  = $_POST['password'];
$role  = $_POST['role'];


// 3) Fetch the user WITHOUT checking otp_verified
$stmt = $conn->prepare("
    SELECT *
      FROM users
     WHERE email = ?
       AND role  = ?
");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();


// 4) Verify credentials
if ($result->num_rows === 1) {
    $u = $result->fetch_assoc();
    if (password_verify($pass, $u['password'])) {
        $_SESSION['user'] = $u;
        // Redirect based on role
        if ($role === 'seller') {
            header('Location: /freelance-website/dashboard_seller.php');
        } else {
            header('Location: /freelance-website/dashboard_buyer.php');
        }
        exit;
    }
}


// 5) On failure
echo "Login failed. Please check your email, password, and role.";