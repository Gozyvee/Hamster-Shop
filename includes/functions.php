<?php
//===== AUTHENTICATION HELPERS =====//
function ifItIsMethod($method = null)
{
    if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
        return true;
    }
    return false;
}

function itisset ($name){
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