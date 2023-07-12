<?php require "includes/admin_header.php"; ?>
<?php 
     if (isset($_GET['change_to_admin']) || isset($_GET['change_to_sub'])) {
        redirect('users.php');
     }
?>
<?php require "includes/admin_navbar.php"; ?>
    <div id="wrapper">
        <div class="container-fluid">
            <div class="col-lg-12">
                <?php 
                    if(isset($_GET['input'])){
                        $input = $_GET['input'];
                    }else{
                        $input = '';
                    }
                    switch($input) {
                        case 'add_user';
                        include "includes/add_user.php";
                        break;

                        case 'edit_user';
                        require "includes/edit_user.php";
                        break;

                        default:
                        require "includes/view_all_users.php";
                        break;
                    }
                ?>
            </div>
        </div>
    </div>    
<?php require "includes/admin_footer.php"; ?>
