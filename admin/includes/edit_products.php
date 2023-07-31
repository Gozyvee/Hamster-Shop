<?php
require_once('config.php');

// Check if the token parameter is present in the URL
if (isset($_GET['token'])) 
{
     $encodedToken = $_GET['token'];

    $id = encryptor('decrypt', $encodedToken);

    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['id'];
       
        echo 'hello' . $product_id;
    }

} 
else 
{
    // Handle invalid or tampered token
    echo "Invalid or tampered token. Please try again.";
}
?>

<form action="" method="post">
    <div class="form-group">
        <label for="title">Firstname</label>
        <input type="text" value="<?php echo1($user_firstname) ?>" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="title">Lastname</label>
        <input type="text" value="<?php echo1($user_lastname) ?>" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">
        <select name="user_role">
            <option value="<?php echo1($user_role); ?>"><?php echo1($user_role) ?></option>
            <?php
            if ($user_role == 'admin') {
                echo " <option value='subscriber'>Subscriber</option>";
            } else {
                echo " <option value='admin'>Admin</option>";
            }
            ?>

        </select>
    </div>

    <div class="form-group">
        <label for="Email">Email</label>
        <input type="email" value="<?php echo1($user_email) ?>" class="form-control" name="user_email">
    </div>
    <div class="form-group">
        <label for="Password">Password</label>
        <input type="password" value="" class="form-control" name="user_password">
    </div>
    <div class="form-group">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <input type="submit" class="btn btn-primary" name="edit_user" value="Edit user">
    </div>

</form>
