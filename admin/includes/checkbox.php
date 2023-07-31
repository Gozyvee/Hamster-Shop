
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
