<?php
require('includes/header.php');
require('includes/navbar.php');

//Removing product from cart
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $removedProductID = $_GET['id'];

    // Check if the product exists in the cart
    if (isset($_SESSION['cart'][$removedProductID])) {
        // Remove the product from the cart
        unset($_SESSION['cart'][$removedProductID]);
        echo "<script> alert('product has been removed') </script>";
        echo "<script> window.location = 'cart.php' </script>";
    }
}
    

?>
<div class="container pt-5">
    <div class="row px-5">
        <div class="col-md-7">
            <div class="shopping-cart">
                <h6>MyCart</h6>
                <hr>
                <?php
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    $total = 0;
                    // Prepare the query outside the loop
                    $query = "SELECT * FROM products WHERE id = ?";
                    $stmt = mysqli_prepare($connection, $query);

                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        // Fetch product details for the current product ID
                        mysqli_stmt_bind_param($stmt, 'i', $product_id);
                        mysqli_stmt_execute($stmt);

                        // Get the result of the query
                        $result = mysqli_stmt_get_result($stmt);

                        // Fetch the product details from the result
                        $product = mysqli_fetch_assoc($result);

                        if ($product) {
                            cartElement(
                                $product['product_img'],
                                $product['product_name'],
                                $product['product_price'],
                                $product['id']
                            );
                            // Calculate product total and add to the overall total
                            $total = $total + $product['product_price'] * $quantity;
                        } else {
                            echo "Product not found for ID: " . $product_id . "<br>";
                        }
                    }

                    // Close the prepared statement
                    mysqli_stmt_close($stmt);
                    // Close the database connection
                    mysqli_close($connection);
                } else {
                    echo "Your cart is empty.";
                }

                ?>
              
            </div>

        </div>
        <div class="col-md-4 offset-md-1 border-rounded mt-5 bg-white h-25">
            <div class="pt-4">
                <h6>PRICE DETAILS</h6>
                <hr>
                <div class="row price-details">
                    <div class="col-md-6">
                        <?php
                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            $count = count($_SESSION['cart']);
                            echo "  <h6>Price($count Items)</h6>";
                        } else {
                            echo "  <h6>Price(0 Items)</h6>";
                        }
                        ?>

                        <h6>Delivery Charges</h6>
                        <hr>
                        <h6>Amount Payable</h6>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fa fa-dollar-sign"></i><?= $total; ?></h6>
                        <h6 style="color: #95C41F;"><i class="fa fa-dollar-sign"></i><?= $delivery = 20 / 100 * $total  ?></h6>
                        <hr>
                        <h6>
                            <i class="fa fa-dollar-sign"></i><?= $total + $delivery; ?>
                        </h6>
                    </div>

                    <div class="checkout text-center w-100 mt-2 mb-2">
                        <button type="button" class="btn text-white" style="background-color: #95c41f;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-credit-card"></i> Proceed To Checkout
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div>
<?php
                // Check if the saved session is set and not empty
                if (isset($_SESSION['saved']) && !empty($_SESSION['saved'])) {
                    echo "<h2>Saved For Later:</h2>";

                    // Iterate through the saved items
                    foreach ($_SESSION['saved'] as $product_id => $quantity) {
                        // ... your code to display saved items ...
                    }
                } else {
                    echo "<p>No items saved for later.</p>";
                }
                ?>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fill Your Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3 row">
                    <label for="inputPassword" name='name' class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" name='name' class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" name='name' class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <input type="text" name="phone" class="form-control" id="phone" required>
                    </div>
                </div>

                <div class="text-center">
                    <button onclick="paywithpaystack()" class="btn" style="background-color:#95c41f;">
                        <i class="fa fa-credit-card"></i>Checkout</button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- <script>
	function paywithpaystack(){
        // e.preventDefault()
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var phone = document.getElementById('phone').value;
		const api = 'pk_live_a6295584cc22efec6535ea80d589b4441d1b0402';
        
		var handler = PaystackPop.setup({
			key: api,
			email: email,
			amount: <?php echo $total * 100; ?>,
			currency: "NGN",
			ref: ''+Math.floor((Math.random() * 1000000000) + 1),
			firstname: name,
			metadata: {
				custom_fields: [
					{
						display_name: name,
						variable_name: name,
						value: phone,
					}
				]
			},
			callback: function(response){
				const referenced = response.reference;
                console.log(response);
				window.location.href='success.php?successfullypaid='+referenced;


			},
			onClose: function(){
				alert('window closed');
                // window.location.href='cancel.php'
			}
		});
		handler.openIframe();
	}
</script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="../dist/js/bootstrap.js"></script> -->