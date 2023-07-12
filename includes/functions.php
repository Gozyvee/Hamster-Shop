<?php
//===== AUTHENTICATION HELPERS =====//
function ifItIsMethod($method=null)
{
    if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
        return true;
    }
    return false;
}

function itisSet ($name){
    if(isset($_POST[$name])){
        return true;
    }
    return false;
}
//===== END AUTHENTICATION HELPERS =====//

//===== FORM HELPERS =====//
// function matchwords ($allowed_characters, $user_input){
//     if (preg_match($allowed_characters, $user_input)) {
//         return true;
//     } 
//     return false;
// }

//cleansing submitted fields
function escape($string){
    global $connection;
   return mysqli_real_escape_string($connection, trim(strip_tags(htmlspecialchars($string))));
}

//=====END FORM HELPERS=====//
//=====USER SPECIFIC HELPERS=====//
function change_to_admin()
{
    global $connection;
    if (isset($_GET['change_to_admin'])) {
        $the_user_id = $_GET['change_to_admin'];

        $query = "UPDATE users SET user_role = 'admin' WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
        mysqli_stmt_execute($stmt);
      
        mysqli_stmt_close($stmt);

    }
}
function change_to_sub()
{
    global $connection;

    if (isset($_GET['change_to_sub'])) {
        $the_user_id = $_GET['change_to_sub'];

        $query = "UPDATE users SET user_role = 'subscriber' WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
        mysqli_stmt_execute($stmt);
     
        mysqli_stmt_close($stmt);
    }
}
//=====END USER SPECIFIC HELPERS=====//

//redirect function
function redirect($location=null){
    return header("Location:". $location);  
}

//checking if user input exists
function ifitexists($user_firstname, $firstname){
    global $connection;
    $query = "SELECT $user_firstname FROM users WHERE $user_firstname = '$firstname'";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) > 0) {
        return true;
    }else{
        return false;
    }
}
function login_user($email=null, $password=null){
    global $connection;

    $email = escape($_POST['email']);
    $password = escape($_POST['password']);

    $stmt = $connection->prepare("SELECT * FROM users WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $db_user_id = $row['id'];
      $db_user_firstname = $row['user_firstname'];
      $db_user_lastname = $row['user_lastname'];
      $db_user_password = $row['user_password'];
      $db_user_role = $row['user_role'];

      if (password_verify($password, $db_user_password)) {
        // login successful, set session variables and redirect to admin page
        $session_token = bin2hex(random_bytes(16));
        $expiry_time = time() + 1800; // Set expiry time to one hour from now
        $stmt = $connection->prepare("INSERT INTO sessions (user_id, session_token, expiry_time) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $db_user_id, $session_token, $expiry_time);
        $stmt->execute();

        $_SESSION['firstname'] = $db_user_firstname;
        $_SESSION['lastname'] = $db_user_lastname;
        $_SESSION['user_role'] = $db_user_role;
        $_SESSION['user_id'] = $db_user_id; 
        $_SESSION['session_token'] = $session_token;
        $_SESSION['expiry_time'] = $expiry_time;

        
        redirect("/shop/admin");
      
        } else {
            return false;
        }
   }
   return true; 
}
