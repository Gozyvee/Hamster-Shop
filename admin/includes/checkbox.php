
<script>
     $(document).ready(function(){
    $('#selectAllBoxes').click(function(event){
        if(this.checked) {
            $('.CheckBoxes').each(function(){
                this.checked = true;
            });
        } else {
            $('.CheckBoxes').each(function(){
                this.checked = false;
            });
        }
    });
  });
</script>
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // CSRF token validation failed, handle the error appropriately (e.g., show an error message, log the attempt, etc.).
        die("CSRF validation failed. Please try again.");
    }
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">


    <?php

function generateRandomKey()
{
    return bin2hex(random_bytes(16)); // Generate a 32-character random key (256 bits)
}

function generateRandomIV()
{
    return random_bytes(openssl_cipher_iv_length('AES-256-CBC')); // Generate a random IV for AES-256-CBC
}

function encrypt($string, $key)
{
    $iv = generateRandomIV();
    $cipherText = openssl_encrypt($string, 'AES-256-CBC', hex2bin($key), OPENSSL_RAW_DATA, $iv);
    $base64CipherText = base64_encode($iv . $cipherText);
    return $base64CipherText;
}

function decrypt($base64CipherText, $key)
{
    $cipherText = base64_decode($base64CipherText);
    $ivLength = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($cipherText, 0, $ivLength);
    $cipherText = substr($cipherText, $ivLength);
    $plainText = openssl_decrypt($cipherText, 'AES-256-CBC', hex2bin($key), OPENSSL_RAW_DATA, $iv);
    return $plainText;
}

// Example usage:
$secretKey = generateRandomKey(); // Generate a new random key for each encryption
$dataToEncrypt = "Sensitive data to encrypt.";
$encryptedData = encrypt($dataToEncrypt, $secretKey);
$decryptedData = decrypt($encryptedData, $secretKey);

echo "Original Data: " . $dataToEncrypt . PHP_EOL;
echo "Encrypted Data: " . $encryptedData . PHP_EOL;
echo "Decrypted Data: " . $decryptedData . PHP_EOL;





if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } elseif (strlen($_POST["password"]) < 8) {
        $passwordErr = "Password must be at least 8 characters long";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $_POST["password"])) {
        $passwordErr = "Password must contain at least one uppercase letter, one lowercase letter, and one digit";
    } else {
        // Password meets the requirements
        $password = $_POST["password"];
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && checkCsrf()) {
    $updateFields = array();

    // Using isset() with $_POST is redundant, as it will always be set in a POST request.
    // However, you should still sanitize and validate user inputs to prevent SQL injection.
    // $user = sanitizeInput($_POST['user']);
    // $product_name = sanitizeInput($_POST['name']);
    // $product_gender = sanitizeInput($_POST['gender']);
    // $product_size = sanitizeInput($_POST['size']);
    // $product_price = sanitizeInput($_POST['product_price']);
    // $product_category = sanitizeInput($_POST['product_category']);

    // Product image handling (similar to the previous code)
    $product_image = handleImageUpload($_FILES['image']);

    // Building the update query dynamically based on the fields that need to be updated
    if (!empty($user)) $updateFields[] = "user = ?";
    if (!empty($product_name)) $updateFields[] = "product_name = ?";
    if (!empty($product_gender)) $updateFields[] = "product_gender = ?";
    if (!empty($product_size)) $updateFields[] = "product_size = ?";
    if (!empty($product_image)) $updateFields[] = "product_img = ?";
    if (!empty($product_price)) $updateFields[] = "product_price = ?";
    if (!empty($product_category)) $updateFields[] = "product_category = ?";

    if (!empty($updateFields)) {
        $query = "UPDATE products SET ";
        $query .= implode(', ', $updateFields);
        $query .= " WHERE id = ?";

        $stmt = mysqli_prepare($connection, $query);

        // Bind the parameters dynamically based on the fields that need to be updated
        $paramTypes = str_repeat('s', count($updateFields)) . 'i';

        // Bind the parameters using a reference to the variables to avoid SQL injection
        $bindParams = array_merge($updateFields, array(&$paramTypes));
        // array_push($bindParams, &$the_user_id);

        call_user_func_array('mysqli_stmt_bind_param', $bindParams);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    session_regenerate_id();

    redirect("products.php");
    exit();
}


  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_post']) && checkCsrf()) {
        // $product_name = sanitizeInput($_POST['name']);
        // $product_category = sanitizeInput($_POST['product_category']);
        // $product_user = sanitizeInput($_POST['product_user']);
        // $product_gender = sanitizeInput($_POST['gender']);

        // // Validate and handle the product image upload
        // $product_image = handleImageUpload($_FILES['image']);

        // $product_size = sanitizeInput($_POST['size']);
        // $product_price = sanitizeInput($_POST['price']);

        if ($product_image === false) {
            die('Invalid image upload');
        }

        $query = "INSERT INTO products (user, product_name, product_img, product_gender, product_size, product_price, product_category) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'sssssss', $product_user, $product_name, $product_image, $product_gender, $product_size, $product_price, $product_category);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        redirect('./products.php');
    } else {
        die('Invalid request');
    }
}

function handleImageUpload($file)
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return false;
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return false; // No file uploaded
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return false; // Exceeded file size limit
        default:
            return false; // Unknown errors
    }

    if ($file['size'] > 2097152) {
        return false; // Limit file size to 2 MB
    }

    // Validate file type (e.g., allow only specific image types)
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileInfo = pathinfo($file['name']);
    $extension = strtolower($fileInfo['extension']);
    if (!in_array($extension, $allowedExtensions)) {
        return false;
    }

    // Generate a unique file name for the image
    $uniqueFileName = uniqid() . '.' . $extension;

    // Move the uploaded file to the desired directory
    $destination = "../img/" . $uniqueFileName;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return false;
    }

    return $uniqueFileName;
}



// <form action="cart.php" method="post">
//     <div class="border rounded">
//         <div class="row bg-white border mb-4">
//             <div class="col-md-3 p-0">
//                 <img src="img/<?php echo $productimg; ?>" alt="Image1" class="img-fluid w-100">
//             </div>
//             <div class="col-md-6 pb-3">
//                 <h5 class="pt-2"><?php echo $productname; ?></h5>
//                 <h5 class="pt-2"><i class="fa fa-naira-sign"></i><?php echo $productprice; ?></h5>
//                 <button type="submit" class="btn text-light fw-bold" name="save" style="background-color: #95C41F">
//                     Save For Later
//                 </button>
//                 <button type="submit" class="btn btn-danger mx-2" name="remove">
//                     <i class="fa-solid fa-trash-can"></i> Remove
//                 </button>
//             </div>
//             <div class="col-md-3 py-5">
//                 <div>
//                     <button class="btn btn-light border" style="border-radius: 50%;">+</button>
//                     <span>1</span>
//                     <button class="btn btn-light border" style="border-radius: 50%;">-</button>
//                 </div>
//             </div>
//         </div>
//     </div>
//     <input type="hidden" name="action" value="process">
//     <input type="hidden" name="product_id" value="<?php echo $productid; ?>">
// </form>


// session_set_cookie_params([
//   'lifetime' => 0, // Set to 0 for session lifetime (until browser is closed)
//   'path' => '/',
//   'domain' => 'localhost/shop/', // Replace with your domain
//   'secure' => true, // Set to true for HTTPS only
//   'httponly' => true, // Set to true to prevent client-side scripts from accessing the cookie
//   'samesite' => 'Strict', // Use 'Strict', 'Lax', or 'None' depending on your needs
// ]);