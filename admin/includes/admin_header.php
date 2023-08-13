<?php 
// Set session timeout to 30 minutes (1800 seconds)
$sessionTimeout = 1800;
// Enable the secure flag to ensure sessions are transmitted only over HTTPS
$secureFlag = true;
// Set the session cookie parameters
session_set_cookie_params($sessionTimeout, '/', $secureFlag, true, 'Strict');

session_start();
ob_start();
require "db.php";
require "functions.php"; 
require_once('config.php');
generateCSRFToken();
?>
<?php 
     if ($_SESSION['user_role'] === 'admin') {
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</head>
