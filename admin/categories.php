<?php require "includes/admin_header.php"; ?>
<?php require "includes/admin_navbar.php"; ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-8">
            <div class="col-xs-12">

                <?php //query to insert into category table 
                if (isset($_POST['submit'])) {
                    $cat_title = escape($_POST['cat_title']);

                    if ($cat_title == "" || empty($cat_title)) {
                        echo "This field should not be empty";
                    } else {
                        $query = "INSERT INTO category(cat_title) VALUES(?)";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_bind_param($stmt, "s", $cat_title);
                        mysqli_stmt_execute($stmt);
                         mysqli_stmt_affected_rows($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }
                ?>

                <form action="#" method="post">
                    <div class="form-group">
                        <label for="cat_title">Add Category</label>
                        <input class="form-control" type="text" name="cat_title">
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="submit" value="Add category">
                    </div>
                   
                </form>

                <?php //redirect to edit category
                if (isset($_GET['update'])) {
                    $cat_id = escape($_GET['update']);
                    include "includes/edit_categories.php";
                }
                ?>

            </div>
            <div class="col-xs-6">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Category title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php //display results from category columns
                            $query = "SELECT * FROM category";
                            $show_category = mysqli_prepare($connection, $query);
                            mysqli_stmt_execute($show_category);
                            mysqli_stmt_bind_result($show_category, $cat_id, $cat_title);

                            while (mysqli_stmt_fetch($show_category)) {

                                echo "<tr>";
                                echo "<td>{$cat_id}</td>";
                                echo "<td>{$cat_title}</td>";
                                echo "<td><a class='btn btn-info' href='categories.php?update={$cat_id}'>Edit</a></td>";
                            ?>
                                <form method="post">
                                    <input type="hidden" name="cat_id" value="<?php echo $cat_id ?>">
                                    <?php
                                    echo '<td><input class="btn btn-danger" type="submit" name="delete" value="Delete"></td>';
                                    ?>
                                </form>
                            <?php
                                echo "</tr>";
                            }
                            mysqli_stmt_close($show_category);
                            ?>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php




?>
<?php //delete category via post
if (ifItIsMethod('post')) {
    $the_cat_id = escape($_POST['cat_id']);

    $query = "DELETE FROM category WHERE cat_id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $the_cat_id);
    mysqli_stmt_execute($stmt);
    redirect('categories.php');
    mysqli_stmt_close($stmt);
}
?>

<?php include "includes/admin_footer.php" ?>