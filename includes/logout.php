<?php session_start(); 
require "/xampp/htdocs/shop/admin/includes/functions.php"; 
 
     unset( $_SESSION['firstname']);
     unset(  $_SESSION['user_role']);
     unset(  $_SESSION['cart']);
    redirect('/shop/index.php');
?>