<?php require "includes/header.php" ?>
<?php 
if(ifItIsMethod('post')){
    checkCsrf();
    $email = escape($_POST['email']);
    $password = escape($_POST['password']);

	if(isset($email) && isset($password)){
        login_user($email, $password);
        if(isLoggedin('admin')){
            redirect('/shop/admin/');
        }
	}else{
		redirect('/shop/index.php');
	}
}
?>
<?php require "includes/navbar.php" ?>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img class="img-fluid " width="90%" src="img/handsome-man-with-laptop.jpg" alt="man with laptop">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" method="post">
                                        <div class="text-center mb-3">
                                            <p>Sign in with:</p>
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
                                        <h6 class="text-center">OR</h6>
                                        <div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input name="email" type="email" class="form-control form-control-user" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group mb-1">
                                            <input name="password" type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <!-- 2 column grid layout -->
                                            <div class="row mb-4">
                                                <div class="col-md-6 d-flex justify-content-center">
                                                    <!-- Checkbox -->
                                                    <div class="form-check mb-3 mb-md-0">
                                                        <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                                                        <label class="form-check-label" for="loginCheck"> Remember me </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 d-flex justify-content-center">
                                                    <!-- Simple link -->
                                                    <a href="forgot-password.php?forgot=<?php echo uniqid(true); ?>">forgot password?</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid mb-3">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                        <input type="submit" name="submit" id="btn-login" class="btn btn-primary btn-lg btn-block d-grid" value="Login">
                                        </div>
                                    </form>
                                    <div class="text-center">
                                        <p>Not a member? <a href="register.php">Register</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</body>

</html>