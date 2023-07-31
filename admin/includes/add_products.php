<?php
if (ifItIsMethod('post')) {
    checkCsrf();
     
    $product_name = escape($_POST['name']);
    $product_category = escape($_POST['product_category']);
    $product_user = escape($_POST['product_user']);
    $product_gender = escape($_POST['gender']);

    $product_image = $_FILES['image']['name'];
    
    // Validate the product image value
    if (!empty($product_image) && preg_match('/^[a-zA-Z0-9_]+\.(jpg|jpeg|png|gif)$/', $product_image)) {
        // The product image value is valid. Proceed with sanitization.
        $product_image = htmlspecialchars($product_image, ENT_QUOTES, 'UTF-8');
    }

    $product_image_temp = $_FILES['image']['tmp_name'];

    $product_size = escape($_POST['size']);
    $product_price = escape($_POST['price']);

    $query = "INSERT into products(user, product_name, product_img, product_gender, product_size, product_price, product_category) VALUES(?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'sssssss', $product_user, $product_name, $product_image, $product_gender, $product_size, $product_price, $product_category);
    mysqli_stmt_execute($stmt);

    move_uploaded_file($product_image_temp, "../img/$product_image");

    mysqli_stmt_close($stmt);
    redirect('./products.php');
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Product name</label>
                    <input type="text" class="form-control" name="name">
                </div>

                <div class="form-group">
                    <label for="Categories"> Product Categories</label>
                    <select name="product_category" id="">
                        <?php
                        $stmt = mysqli_prepare($connection, "SELECT cat_id, cat_title FROM category");
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $cat_id = $row['cat_id'];
                            $cat_title = $row['cat_title'];
                            echo "<option value='$cat_title'>{$cat_title}</option>";
                        }
                        mysqli_stmt_close($stmt);
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="users">Users</label>
                    <select name="product_user" id="">
                        <?php
                        $query = "SELECT id, user_firstname, user_lastname FROM users";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $user_firstname = $row['user_firstname'];
                            $user_lastname = $row['user_lastname'];

                            echo "<option value='{$user_lastname} {$user_firstname}'>{$user_lastname}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="gender">Product Gender</label>
                    <?php
                    // Retrieve gender options from the database
                    $query = "SELECT id, gender FROM gender";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    // Check if there are options available
                    if (mysqli_num_rows($result) > 0) {

                        echo '<select name="gender">';
                        while ($row = mysqli_fetch_assoc($result)) {
                            $option_id = $row['id'];
                            $option_name = $row['gender'];
                            echo "<option value='$option_name'>$option_name</option>";
                        }
                        echo '</select>';
                    }

                    ?>
                </div>

                <div class="form-group">
                    <label for="title">Post Image</label>
                    <input type="file" class="form-control" name="image">
                </div>
                <div class="form-group">
                    <label for="title">Product size</label>
                    <input type="text" class="form-control" name="size">
                </div>
                <div class="form-group">
                    <label for="title">Product price</label>
                    <input type="text" class="form-control" name="price">
                </div>
                <div class="form-group">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <input type="submit" class="btn btn-primary" name="create_post" value="Publish product">
                </div>

            </form>
        </div>
        
    </div>
</div>