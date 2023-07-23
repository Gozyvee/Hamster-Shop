<?php require "includes/header.php";?>
<?php
if (ifItIsMethod('post') && itisset('submit')){
    $firstname =  escape($_POST['firstname']);
    $lastname    =  escape($_POST['lastname']);
    $email    =  escape($_POST['emailaddress']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password =  escape($_POST['password']);
    $confirmpassword =  escape($_POST['repeatpassword']);

    $error = [
        'firstname' => '',
        'email' => '',
        'password' => ''
    ];

    if (strlen($firstname) < 4 ) {
        $error['firstname'] = 'Cannot be less than 4 characters';
    }
    if ($firstname == '') {
        $error['firstname'] = 'Field cannot be empty';
    }
    if ( $email == '') {
        $error['email'] = 'Email cannot be empty';
    }
    if (ifitexists('user_email', $email)) {
        $error['email'] = 'Email already exists';
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error['email'] = 'Please enter a correct email format';
    }
    if ($password == '') {
        $error['password'] = 'Password cannot be empty';
    }
    if ($password !== $confirmpassword) {
        $error['password'] = 'Passwords do not match!';
    }
    //condition to register and login new user
    foreach ($error as $key => $value) {
        if (empty($value)) {
            unset($error[$key]);
        }
    }
    if (empty($error)) {
       $user_password = password_hash( $password, PASSWORD_BCRYPT, array('cost' => 12));
       $query = "INSERT INTO users (user_firstname, user_lastname, user_email, user_password, user_role)";
       $query .= "VALUES(?, ?, ?, ?, 'subscriber')";
       $stmt = mysqli_prepare($connection, $query);
       mysqli_stmt_bind_param($stmt, "ssss", $firstname, $lastname, $email, $user_password);
       mysqli_stmt_execute($stmt);
       mysqli_stmt_close($stmt);
       redirect('./login.php');
       exit();       
    }
}
?>
<!-- Navigation Bar -->
<?php require "includes/navbar.php";?>
<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5">
                        <img class="img-fluid" src="img/happy-man.jpg" alt="happy man">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form method="post">
                                <div class="text-center mb-3">
                                    <p>Sign up with:</p>
                                    <button type="button" class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-facebook-f"></i>
                                    </button>

                                    <button type="button" class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-google"></i>
                                    </button>

                                    <button type="button" class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-twitter"></i>
                                    </button>

                                    <button type="button" class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-github"></i>
                                    </button>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="firstname" placeholder="FirstName">
                                        <p><?php echo isset($error['firstname']) ? $error['firstname'] : '' ?></p>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <input type="text" class="form-control form-control-user" name="lastname" placeholder="LastName">
                                        <p><?php echo isset($error['firstname']) ? $error['firstname'] : '' ?></p>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="email" class="form-control form-control-user" name="emailaddress" placeholder="EmailAddress">
                                    <p><?php echo isset($error['email']) ? $error['email'] : ''  ?></p>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="password" placeholder="Password">
                                        <p><?php echo isset($error['password']) ? $error['password'] : ''  ?></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" name="repeatpassword" placeholder="RepeatPassword">
                                        <p><?php echo isset($error['repeatpassword']) ? $error['repeatpassword'] : ''  ?></p>
                                    </div>
                                </div>
                                <input href="login.php" type="submit" name="submit" id="btn-login" class="btn btn-primary btn-lg btn-block d-grid" value="Register">
                                <hr>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>