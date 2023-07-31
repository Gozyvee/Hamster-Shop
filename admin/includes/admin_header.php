<?php 
session_set_cookie_params([
  'lifetime' => 0, // Set to 0 for session lifetime (until browser is closed)
  'path' => '/',
  'domain' => 'localhost/shop/', // Replace with your domain
  'secure' => true, // Set to true for HTTPS only
  'httponly' => true, // Set to true to prevent client-side scripts from accessing the cookie
  'samesite' => 'Strict', // Use 'Strict', 'Lax', or 'None' depending on your needs
]);
session_start();
ob_start();
require "db.php";
require "functions.php"; 
generateCSRFToken();
?>
<?php 
     if ($_SESSION['user_role'] == 'admin') {
        // User is authorized, allow access to the page
      } else {
        // User is not authorized, redirect to login page
        redirect("../index.php");
        exit();
      } 
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
