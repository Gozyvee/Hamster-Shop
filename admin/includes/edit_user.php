<?php
// Check if the user ID to edit is provided via GET request
if (isset($_GET['edit_user'])) {
    $encodedToken = $_GET['edit_user'];
    $the_user_id = encryptor('decrypt', $encodedToken);

    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $the_user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if a user with the given ID exists before retrieving data
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_role = $row['user_role'];
    } else {
        die('go away');
    }
}

// Check if the form has been submitted via POST and check if csrf token match
if (ifItIsMethod('post') && checkCsrf()) {
   
    // Retrieve and sanitize form data
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_email = $_POST['user_email'];
    $user_role = $_POST['user_role'];
    $user_password = $_POST['user_password'];
    // Check if any fields have changed before executing the UPDATE query
    if 
    (
        isset($user_firstname) ||
        isset($user_lastname) ||
        isset($user_email) ||
        isset($user_role) ||
        isset($user_password)
    ) {
        $query = "UPDATE users SET ";
        $fields = array();

        // Update user_firstname
        if (isset($user_firstname)) {
            $fields[] = "user_firstname = '" . escape($user_firstname) . "'";
        }
        // Update user_lastname
        if (isset($user_lastname)) {
            $fields[] = "user_lastname = '" . escape($user_lastname) . "'";
        }
        // Update user_role
        if (isset($_POST['user_role'])) {
            $fields[] = "user_role = '" . escape($user_role) . "'";
        }
        // Update user_email
        if (isset($_POST['user_email'])) {
            $fields[] = "user_email = '" . filter_var(escape($user_email), FILTER_SANITIZE_EMAIL) . "'";
        }
        if (isset($_POST['user_password'])) {
            $fields[] = "user_password = '" . password_hash(escape($user_password), PASSWORD_BCRYPT, array('cost' => 12)). "'";
        }

        $query .= implode(', ', $fields);
        $query .= " WHERE id = ? ";

        // Prepare the SQL query for execution and bind the parameter
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $the_user_id);

        // Execute the prepared statement to update the user's information
        mysqli_stmt_execute($stmt);

        session_regenerate_id();

        // Redirect to a secure page after successful update
        redirect("users.php");
    }
    exit();
}

?>
<form method="post">
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