<?php 
require "includes/admin_header.php"; 
require "includes/admin_navbar.php";
?>

<div id="wrapper">
<div id="page-wrapper">

<div class="container-fluid">
  <div class="col-lg-12">
        <?php 
            if(isset($_GET['source'])) {
                $source = $_GET['source'];
            }else {
                $source = '';
            }
                switch($source) {
                    case 'add_product';
                    include "includes/add_products.php";
                    break;

                    case 'edit_product';
                    include "includes/edit_post.php";
                    break;

                    default: 
                    include "includes/view_products.php";
                    break;
            }

             
        ?>
       
  </div>


</div>
</div>
</div>
<!-- /.container-fluid -->
<?php include "includes/admin_footer.php" ?>