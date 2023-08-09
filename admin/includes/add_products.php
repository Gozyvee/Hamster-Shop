<?php
if (ifItIsMethod('post')) {
    if (checkCsrf()) {
        $product_name = escape($_POST['name']);
        $product_category = escape($_POST['product_category']);
        $product_user = escape($_POST['product_user']);
        $product_gender = escape($_POST['gender']);

        $product_image = $_FILES['image'];
        $product_image_temp = $product_image['tmp_name'];

        $product_size = escape($_POST['size']);
        $product_price = escape($_POST['price']);

        // Validate file type (e.g., allow only specific image types)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileInfo = pathinfo($product_image['name']);
        $extension = strtolower($fileInfo['extension']);
        if (!in_array($extension, $allowedExtensions)) {
            return false;
        }

        if (empty($product_name) || empty($product_price) || empty($product_gender) || empty($product_image) || empty($product_size)) {
            $errors[] = 'Please fill all required fields.';
            echo $errors;
        }

        if ($product_image['size'] < 5097152) {
            // Generate a unique file name for the image
            $uniqueImageName = uniqid() . '.' . $extension;
            // Move the uploaded file to the desired directory
            $destination = "../img/" . $uniqueImageName;   
            // Move uploaded file to the desired location
            if(move_uploaded_file($product_image_temp, $destination)){
                $query = "INSERT into products(user, product_name, product_img, product_gender, product_size, product_price, product_category) VALUES(?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, 'sssssss', $product_user, $product_name, $uniqueImageName, $product_gender, $product_size, $product_price, $product_category);
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    redirect('./products.php');
                    exit();
                } else {
                    die('Error while executing the database query.');
                }
            }        
        }else{
            echo "file is larger than 5mb";
        }
       
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Product name</label>
                    <input type="text" class="form-control" name="name" required minlength="10" maxlength="50">
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
                    <input type="file" class="form-control" name="image" required accept="image/jpeg, image/png, image/gif, image/jpg">
                </div>
                <div class="form-group">
                    <label for="title">Product size</label>
                    <input type="text" class="form-control" name="size" required minlength="3" maxlength="10">
                </div>
                <div class="form-group">
                    <label for="title">Product price</label>
                    <input type="text" class="form-control" name="price" required minlength="3" maxlength="15">
                </div>
                <div class="form-group">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <input type="submit" class="btn btn-primary" name="create_post" value="Publish product">
                </div>

            </form>
        </div>

    </div>
</div>