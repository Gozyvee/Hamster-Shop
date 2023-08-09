<?php
//===== AUTHENTICATION HELPERS =====//
function ifItIsMethod($method=null)
{
    if ($_SERVER['REQUEST_METHOD'] === strtoupper($method)) {
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

//echoing form data 
function echo1 ($string=null){
    echo isset($string) ? htmlspecialchars($string, ENT_QUOTES, 'UTF-8') : '';
    return;
}

//cleansing submitted fields
function escape($string){
    global $connection;
   return trim(strip_tags(htmlspecialchars($string, ENT_QUOTES, 'UTF-8')));
}

//=====END FORM HELPERS=====//
//=====USER SPECIFIC HELPERS=====//

//changing user to admin func
function change_to_admin()
{
    global $connection;
       if (isset($_GET['change_to_admin'])) {
        $encodedToken = $_GET['change_to_admin'];
        $the_user_id = encryptor('decrypt', $encodedToken);
    
        $query = "UPDATE users SET user_role = 'admin' WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
        mysqli_stmt_execute($stmt);
      
        mysqli_stmt_close($stmt);

    }
}
//changing user to subscriber func
function change_to_sub()
{
    global $connection;

    if (isset($_GET['change_to_sub'])) {
        $encodedToken = $_GET['change_to_sub'];
        $the_user_id = encryptor('decrypt', $encodedToken);

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
// function ifitexists($column, $postInput){
//     global $connection;
//     $query = "SELECT * FROM users WHERE $column = ?";
//     $stmt = mysqli_prepare($connection, $query);
//     mysqli_stmt_bind_param($stmt, 's', $postInput);
//     $result = mysqli_stmt_get_result($stmt);

//     if(mysqli_num_rows($result) > 0) {
//         return true;
//     }else{
//         return false;
//     }
// }

function countCart (){
    // global $connection;
    if($_GET['cartCount']){
       
        echo count($_SESSION['cart']);
    }
    
}

function imgSanitizer($product_image){
      // Validate the product image value
      if (!empty($product_image) && preg_match('/^[a-zA-Z0-9_]+\.(jpg|jpeg|png|gif)$/', $product_image)) {
        // The product image value is valid. Proceed with sanitization.
        $product_image = htmlspecialchars($product_image, ENT_QUOTES, 'UTF-8');
    }
    return $product_image;
}

//Checking user login status and role
function isLoggedin($user_role=null){
    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == $user_role){
        return true;
    }
    return false;
}

// Generate CSRF token and store it in the user's session for form submission
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
    }
}

//Checking if tokens match and letting user send request
function checkCsrf(){
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    return true;
    }
}

function sanitizeFileName($filename) {
    // Remove any non-alphanumeric characters and replace spaces with underscores
    $filename = preg_replace("/[^a-zA-Z0-9_.]/", "", str_replace(" ", "_", $filename));
    return $filename;
}

//deleting user from database with post method
function postDelete($table=null, $redirect=null){
    global $connection;
    if (ifItIsMethod('post')) {
        if (isset($_POST['user_id']) && isset($_POST['delete'])) {
            $the_user_id = $_POST['user_id'];
    
            $query = "DELETE FROM $table WHERE id = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
            mysqli_stmt_execute($stmt);
            redirect($redirect);
            mysqli_stmt_close($stmt);
        }
    }
    return;
}


//login function
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
      $db_user_password = $row['user_password'];
      $db_user_role = $row['user_role'];

      if (password_verify($password, $db_user_password)) {
        // login successful, set session variables and redirect to admin page
        $session_token = bin2hex(random_bytes(16));
        $expiry_time = time() + 1800; // Set expiry time to one hour from now
        $stmt = $connection->prepare("INSERT INTO sessions (user_id, session_token, expiry_time) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $db_user_id, $session_token, $expiry_time);
        $stmt->execute();

        session_regenerate_id(true);
        $_SESSION['firstname'] = $db_user_firstname;
        $_SESSION['user_role'] = $db_user_role;
        
        redirect("/shop/admin");
      
        } else {
            return false;
        }
   }
   return true; 
}

