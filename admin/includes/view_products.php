<?php
if (isset($_POST['checkBoxArray'])) {
    $checkbox = $_POST['checkBoxArray'];

    foreach ($checkbox as $key) {
        $bulkoptions = isset($_POST['bulk_options']) ? $_POST['bulk_options'] : '';
        // Validate and sanitize the input value
        $validOptions = array('clone');

        if (!in_array($bulkoptions, $validOptions)) {
            die("not found");
        }

        // switch-case logic for the bulk action here, using prepared statements for database operations.
        switch ($bulkoptions) {
            case 'clone';
                $query = "SELECT * FROM products WHERE id = ?";
                $clone_stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($clone_stmt, "i", $key);
                mysqli_stmt_execute($clone_stmt);
                $result = mysqli_stmt_get_result($clone_stmt);

                while ($row = mysqli_fetch_array($result)) {
                    $product_user = $row['user'];
                    $product_name = $row['product_name'];
                    $product_image = $row['product_img'];
                    $product_gender = $row['product_gender'];
                    $product_size = $row['product_size'];
                    $product_price = $row['product_price'];
                    $product_category = $row['product_category'];

                    $query = "INSERT into products(user, product_name, product_img, product_gender, product_size, product_price, product_category) VALUES(?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, 'sssssss', $product_user, $product_name, $product_image, $product_gender, $product_size, $product_price, $product_category);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
                header("Location: products.php");
                break;
        }
    }
}
?>
<form method="post">
    <table class="table table-bordered border-dark table-hover">
        <div id="bulkOptionsContainer" class="col-md-6 mb-2">
            <select class="form-control" name="bulk_options" id="">
                <option value="clone">Clone</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="submit" name="submit" class="btn btn-success mb-3" value="Apply">
            <a href="products.php?source=add_product" class="btn btn-primary mb-3">Add New</a>
        </div>
        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>id</th>
                <th>User</th>
                <th>Product Name</th>
                <th>Image</th>
                <th>Gender</th>
                <th>Size</th>
                <th>Price</th>
                <th>Category </th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php //Display and order products in descending order
            $query = "SELECT * FROM products ORDER BY id DESC";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $product_id, $product_user, $product_name, $product_image, $product_gender, $product_size, $product_price, $product_category);

            while (mysqli_stmt_fetch($stmt)) {
                echo "<tr>";
            ?>
                <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?= htmlspecialchars($product_id); ?>'></td>
                <?php
                echo "<td>" . escape($product_id) . "</td>";
                echo "<td>" . escape($product_user) . "</td>";
                echo "<td>" . escape($product_name) . "</td>";
                echo "<td><img src='../img/{$product_image}' width='100' alt='image'></td>";
                echo "<td>" . escape($product_gender) . "</td>";
                echo "<td>" . escape($product_size) . "</td>";
                echo "<td>" . escape($product_price) . "</td>";
                echo "<td>" . escape($product_category) . "</td>";


                // Encrypt the sensitive data to create the token
                $encryptedToken = encryptor('encrypt', $product_id);
                // URL encode the encrypted token before including it in the URL
                $encodedToken = urlencode($encryptedToken);
                // Generate the link with the encrypted token as a query parameter
                $edit_link = "products.php?source=edit_product&token=". $encodedToken;

                echo "<td> <a class='btn btn-info ' href='$edit_link'>Edit</a></td>";

                ?>

                <form method="post">
                    <input type="hidden" name="user_id" value="<?= $product_id ?>">
                    <?php
                    echo '<td><input class="btn btn-danger" type="submit" name="delete" value="Delete"></td>';
                    ?>
                </form>
            <?php

                echo "</tr>";
            }
            ?>

        </tbody>
    </table>
</form>
<?php
change_to_admin();
change_to_sub();

//deleting user from database with post method
postDelete('products','products.php');
?>
<script>
    $(document).ready(function() {
        $('#selectAllBoxes').click(function(event) {
            if (this.checked) {
                $('.CheckBoxes').each(function() {
                    this.checked = true;
                });
            } else {
                $('.CheckBoxes').each(function() {
                    this.checked = false;
                });
            }
        });
    });
</script>