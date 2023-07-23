<?php session_start(); 
require "/xampp/htdocs/shop/admin/includes/functions.php"; 
?>
<?php 
      $_SESSION['firstname'] = null;
      $_SESSION['user_role'] = null;

    redirect('/shop/index.php');
?>