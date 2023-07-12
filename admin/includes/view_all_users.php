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

            echo "<td> <a class='btn btn-success' href='users.php?change_to_admin={$user_id}'>Admin</a></td>";
            echo "<td> <a class='btn btn-info' href='users.php?change_to_sub={$user_id}'>Sub</a></td>";
            echo "<td> <a class='btn btn-warning' href='users.php?input=edit_user&edit_user={$user_id}'>Edit</a></td>";
            echo "<td> <a class='btn btn-danger' href='users.php?delete={$user_id}'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
      
    </tbody>
</table>
<?php 
    change_to_admin();
    change_to_sub();
?>