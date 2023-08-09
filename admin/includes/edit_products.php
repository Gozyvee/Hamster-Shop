<?php
// Check if the token parameter is present in the URL, decrypt and assign product information to corresponding variables for further use.
if (isset($_GET['token'])) {
    $encodedToken = $_GET['token'];

    $the_user_id = encryptor('decrypt', $encodedToken);

    $query = "SELECT id, user, product_name, product_img, product_gender, product_size, product_price, product_category
    FROM products 
    WHERE id = ? 
    LIMIT 1";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $the_user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['id'];
        $product_user = $row['user'];
        $product_name = $row['product_name'];
        $product_image = $row['product_img'];
        $product_gender = $row['product_gender'];
        $product_size = $row['product_size'];
        $product_price = $row['product_price'];
        $product_category = $row['product_category'];
    } else {
        // Handle case when no product with the given ID is found
        echo "Invalid or tampered token. Please try again.";
    }
}

// Check if the form has been submitted via POST and check if csrf token match
if (ifItIsMethod('post') && checkCsrf()) {
    $updateFields = array();
    $user = $_POST['user'];
    $product_name = $_POST['name'];
    $product_gender = $_POST['gender'];
    $product_size = $_POST['size'];
    $product_image = $_FILES['image']['name'];
    $product_price = $_POST['product_price'];
    $product_category = $_POST['product_category'];

    if (isset($user)) $updateFields[] = "user = '" . escape($user) . "'";
    if (isset($product_name)) $updateFields[] = "product_name = '" . escape($product_name) . "'";
    if (isset($product_gender)) $updateFields[] = "product_gender = '" . escape($product_gender) . "'";
    if (isset($product_size)) $updateFields[] = "product_size = '" . escape($product_size) . "'";
    if (isset($product_image)) $updateFields[] = "product_img = '" . $product_image . "'";
    if (isset($product_price)) $updateFields[] = "product_price = '" . escape($product_price) . "'";
    if (isset($product_category)) $updateFields[] = "product_category = '" . escape($product_category) . "'";

    // Check if any fields have changed before executing the UPDATE query
    if (!empty($updateFields)) {
        $query = "UPDATE products SET ";
        $query .= implode(', ', $updateFields);
        $query .= " WHERE id = ? ";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
        mysqli_stmt_execute($stmt);
    }

    session_regenerate_id();

    // Redirect to a secure page after successful update
    redirect("products.php");
    exit();
}
?>


<form method="post">
    <div class="form-group">
        <label for="title">User</label>
        <input type="text" value="<?php echo1($product_user) ?>" class="form-control" name="user" required minlength="10" maxlength="50">
    </div>
    <div class="form-group">
        <label for="title">Product Name</label>
        <input type="text" value="<?php echo1($product_name) ?>" class="form-control" name="name" required minlength="10" maxlength="50">
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
        <label for="Email">Product Image</label>
        <input type="file" class="form-control" name="image">
    </div>
    <div class="form-group">
        <label for="Password">Product Size</label>
        <input type="text" value="<?php echo1($product_size) ?>" class="form-control" name="size" required minlength="3" maxlength="10>
    </div>
    <div class="form-group">
        <label for="Password">Product Price</label>
        <input type="text" value="<?php echo1($product_price) ?>" class="form-control" name="product_price" required minlength="4" maxlength="15">
    </div>
    <div class="form-group">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <input type="submit" class="btn btn-primary" name="edit_product" value="Edit Product">
    </div>

</form>