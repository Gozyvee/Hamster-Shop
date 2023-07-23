<?php
if (isset($_GET['edit_user'])) {
    $the_user_id = $_GET['edit_user'];

    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $the_user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['id'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_role = $row['user_role'];
    }
}


if (ifItIsMethod('post')) {

    $user_firstname = escape($_POST['user_firstname']);
    $user_lastname = escape($_POST['user_lastname']);
    $email = escape($_POST['user_email']);
    $user_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = escape($_POST['user_password']);
    $user_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
    $user_role = escape($_POST['user_role']);


    $query = "UPDATE users SET ";
    $query .= "user_firstname = ?, ";
    $query .= "user_lastname = ?, ";
    $query .= "user_role = ?, ";
    $query .= "user_email = ?, ";
    $query .= "user_password = ? ";
    $query .= "WHERE id = ? ";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'sssssi', $user_firstname, $user_lastname, $user_role, $user_email, $user_password, $the_user_id);
    mysqli_stmt_execute($stmt);

    session_regenerate_id();

    // Redirect to a secure page after successful login
    header("Location: users.php");
    exit();
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
        <input type="submit" class="btn btn-primary" name="edit_user" value="Edit user">
    </div>

</form>