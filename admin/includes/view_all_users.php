<table class="table table-bordered border-dark table-hover">
    <thead>
        <tr>
            <th>id</th>
            <th>Email</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Role</th>
            <th>Change to</th>
            <th>Change to</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM users";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['id'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_password = $row['user_password'];
            $user_role = $row['user_role'];

            echo "<tr>";
            echo "<td>{$user_id}</td>";
            echo "<td>{$user_email}</td>";
            echo "<td>{$user_firstname}</td>";
            echo "<td>{$user_lastname}</td>";
            echo "<td>{$user_role}</td>";

              // Encrypt the sensitive data to create the token
              $encryptedToken = encryptor('encrypt', $user_id);
              // URL encode the encrypted token before including it in the URL
              $encodedToken = urlencode($encryptedToken);
              // Generate the link with the encrypted token as a query parameter
              $edit_link = "users.php?change_to_admin=". $encodedToken;
              $editSecondLink = "users.php?change_to_sub=". $encodedToken;
              $updateUser = "users.php?input=edit_user&edit_user=". $encodedToken;

            echo "<td> <a class='btn btn-success' href='$edit_link'>Admin</a></td>";
            echo "<td> <a class='btn btn-info' href='{$editSecondLink}'>Sub</a></td>";
            echo "<td> <a class='btn btn-warning' href='{$updateUser}'>Edit</a></td>";
        ?>
            <form method="post">
                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
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
<?php
change_to_admin();
change_to_sub();

//deleting user from database with post method
postDelete('users','users.php');
?>