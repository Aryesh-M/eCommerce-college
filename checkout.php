<link rel="stylesheet" href="./css/styles.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<?php

$id = 0;
$userID = 0;
$errors = array();

if (isset($_COOKIE['user_id'])) {
    $userID = $_COOKIE['user_id'];
}
$cookieName = 'product_id';
require('./mysqli_oop_connect.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $qty = $_POST['gqty'];
    $first_name = trim(strip_tags($_POST['firstName']));
    $last_name = trim(strip_tags($_POST['lastName']));
    $paymentMethod = $_POST['payment'];
    $cardHolderName = trim(strip_tags($_POST['cardholdername']));
    $cardHolderEmail = trim(strip_tags($_POST['cardholderemail']));
    $billingAddress = trim(strip_tags($_POST['billingaddress']));
    $cardNumber = trim(strip_tags($_POST['cardnumber']));
    $expiryDate = trim(strip_tags($_POST['expirydate']));
    $securityCode = trim(strip_tags($_POST['securitycode']));
    $is_empty = false;

    if (isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] != 0) {
        $id = $_COOKIE[$cookieName];
        $q = "SELECT * FROM `bookstore`.`bookinventory` WHERE `id` IN ('$id')";
        $result = $mysqli->query($q);
        if ($mysqli->error) {
            echo "<h1 class='error'>Something went wrong!</h1>";
        } else {
            $count = mysqli_num_rows($result);
            if ($count == 0) {
                echo "<h1 class='warning'>Product not found! <a href='index.php' style='font-size: 0.7em;'>Want to go back?</a></h1>";
            }
        }
        $row = $result->fetch_object();
    }

    if (empty($first_name)) {
        $is_empty = true;
        $errors['firstName'] = "<p style='color:red;'>First Name is mandatory.</p>";
    }
    if ($paymentMethod == 'online') {
        if (empty($cardHolderName)) {
            $is_empty = true;
            $errors['cardholdername'] = "<p style='color:red;'>Card Holder Name is mandatory.</p>";
        }
        if (empty($billingAddress)) {
            $is_empty = true;
            $errors['billingaddress'] = "<p class='error'>Billing Address is mandatory.</p>";
        }
        if (empty($cardNumber)) {
            $is_empty = true;
            $errors['cardnumber'] = "<p class='error'>Card Number is mandatory.</p>";
        }
        if (empty($expiryDate)) {
            $is_empty = true;
            $errors['expirydate'] = "<p class='error'>Expiration Date is mandatory.</p>";
        }
        if (empty($securityCode)) {
            $is_empty = true;
            $errors['securitycode'] = "<p class='error'>Security Code is mandatory.</p>";
        }
    }
    if (!$is_empty) {
        $fn = $mysqli->real_escape_string(trim($first_name));
        $ln = !empty($last_name) ? $mysqli->real_escape_string(trim($last_name)) : "NULL";
        $cn = !empty($cardHolderName) ? $mysqli->real_escape_string(trim($cardHolderName)) : "NULL";
        $ce = !empty($cardHolderEmail) ? $mysqli->real_escape_string(trim($cardHolderEmail)) : "NULL";
        $ba = !empty($billingAddress) ? $mysqli->real_escape_string(trim($billingAddress)) : "NULL";
        $cn = !empty($cardNumber) ? $mysqli->real_escape_string(trim($cardNumber)) : "NULL";
        $ed = !empty($expiryDate) ? $mysqli->real_escape_string(trim($expiryDate)) : "NULL";
        $sc = !empty($securityCode) ? $mysqli->real_escape_string(trim($securityCode)) : "NULL";

        $subtotal = 0;
        $tax = 0;
        $total = 0;
        $quantity = 0;
        if (isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] != 0) {
            $id = $_COOKIE[$cookieName];
            $q = "SELECT * FROM `bookstore`.`bookinventory` WHERE `id` IN ('$id')";
            $result = $mysqli->query($q);
            while ($row = $result->fetch_object()) {
                $subtotal = $row->sub_total;
                $tax = $row->tax;
                $total = $row->total;
                $quantity = $row->quantity - $_POST['gqty'];
            }
        }
        $qry = "INSERT INTO `bookstore`.`orders`(`user_id`, `sub_total`, `tax`, `total`,`quantity`) VALUES (?,?,?,?,?)";
        $stmt = $mysqli->prepare($qry);
        $stmt->bind_param('idddi', $userID, $subtotal, $tax, $total, $qty);
        $stmt->execute();
        if ($stmt->affected_rows == 1) {
            $query1 = $mysqli->prepare("UPDATE `bookstore`.`bookinventory` SET quantity=? WHERE id = ?");
            $query1->bind_param('ii', $quantity, $id);
            $query1->execute();
            $query1->close();

            echo "<script>
            localStorage.setItem('order_checkout', true);
            window.location.href = './success.php';
        </script>";
        } else {
            echo "<h3 style='color:red'>Something went wrong!</h3>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        button[type=submit] {
            width: 8em;
        }

        input {
            border-radius: 7px;
        }

        .cart-book {
            margin: 0.5em;
            border: 3px solid #90caf9;
            padding: 1em;
            text-align: center;
            background-color: #1976D2;
            border-radius: 20px;
            color: white;
            width: 50%;
        }

        .cart {
            display: flex;
            margin: auto;
            width: 80%;
            align-content: center;
            flex-direction: column;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 2em;
        }

        .warning {
            color: orange;
        }

        .error {
            color: red;
        }

        .book-item {
            display: flex;
            justify-content: center;
        }

        .desc {
            padding-left: 2em;
            text-align: left;
            font-weight: bold;

        }

        .desc>.book-title {
            font-size: 1.5em;
            font-weight: 600;
        }

        .desc span {
            font-weight: normal !important;
        }

        .desc span::before {
            content: '$';
        }

        .author {
            font-size: 1.1em;
        }

        .price::before {
            content: "$";
            color: #ff0072;
        }

        .price {
            color: #ff0072;
            font-size: 1.3em;
        }

        body {
            background-color: #d4d2df;
        }

        .quantity {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .quantity button {
            margin: 0.2em;
        }

        .quantity input {
            width: 3em;
            height: 3em;
        }
    </style>
</head>
<h2 class="text-center"><img onclick="window.location.href = 'index.php'" class="logo" src="images/logo.png" alt="Bookstore Logo"></h2>
<div class="cart">
    <?php
    if (isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] != 0) {
        $id = $_COOKIE[$cookieName];
        $q = "SELECT * FROM `bookstore`.`bookinventory` WHERE `id` IN ('$id')";
        $result = $mysqli->query($q);
        if ($mysqli->error) {
            echo "<h1 class='error'>Something went wrong!</h1>";
        } else {
            $count = mysqli_num_rows($result);
            if ($count == 0) {
                echo "<h1 class='warning'>Product not found! <a href='index.php' style='font-size: 0.7em;'>Want to go back?</a></h1>";
                return;
            }
        }
        while ($row = $result->fetch_object()) {
            $books = $row;
            $jsonobj = json_encode($row);
            echo "<div class='cart-book'>
                    <img src='https://via.placeholder.com/150' alt='Image Placeholder' />
                    <div class='desc'>
                        <p class='book-title'>$row->book_title</p>
                        <p class='sub-total'>Sub Total: <span id='sub-total'>$row->sub_total</span></p>
                        <p class='tax'>Tax: <span id='tax'>$row->tax</span></p>
                        <p class='total'>Total: <span id='total'>$row->total</span></p>
                    </div>
                </div>
                <div class='quantity'>
                    <h4>Quantity: </h4>&nbsp;&nbsp;
                    <button class='btn btn-danger' title='decrement' id='dec' disabled onclick='count(1,$row->quantity)'>-</button>
                    <input id='qty' type='number' name='quantity' value='1' title='quantity' min='1' onchange='count(0,$row->quantity)'>
                    <button class='btn btn-success' title='increment' id='inc' onclick='count(2,$row->quantity)'>+</button>
                    &nbsp;&nbsp;<h2>/$row->quantity (total)</h2>
                </div>";
        }
    } else {
        echo "<script>window.location.href = './index.php';</script>";
    }
    ?>
</div>
<form action="checkout.php" method="POST" id="checkout-form">
    <fieldset>
        <legend>Checkout Form</legend>
        <input type="hidden" name="gqty" id="gqty" value="1">
        <?php if (array_key_exists("firstName", $errors)) echo $errors['firstName']; ?>
        <p>First Name<span class="error">*</span>: <input type="text" name="firstName" value="<?php if (isset($_POST['firstName'])) echo ($_POST['firstName']); ?>" /></p>
        <p>Last Name: <input type="text" name="lastName" /></p>
        <p>Payment method:
            <input type="radio" name="payment" id="cash" onclick="showCard(false)" value="cash"><label for="cash">Cash</label>
            <input type="radio" name="payment" id="online" onclick="showCard(true)" value="online" checked><label for="online">Credit/Debit</label>
        <fieldset id="online-payment">
            <legend>Online payment</legend>
            <div id="payment-form">
                <?php if (array_key_exists("cardholdername", $errors)) echo $errors['cardholdername']; ?>
                <?php if (array_key_exists("cardholderemail", $errors)) echo $errors['cardholderemail']; ?>
                <p>Cardholder Name<span class="error">*</span>: <input type="text" name="cardholdername">&nbsp;&nbsp;&nbsp;
                    Cardholder Email: <input type="email" name="cardholderemail"></p>
                <?php if (array_key_exists("billingaddress", $errors)) echo $errors['billingaddress']; ?>
                <p style="display:flex;align-items: center;">
                    Billing Address<span class="error">*</span>: <textarea type="text" name="billingaddress" rows="3" cols="50">
                        </textarea>
                </p>
                <?php if (array_key_exists("cardnumber", $errors)) echo $errors['cardnumber'];
                if (array_key_exists("expirydate", $errors)) echo $errors['expirydate'];
                if (array_key_exists("securitycode", $errors)) echo $errors['securitycode']; ?>

                <p>Card Number<span class="error">*</span>: <input type="number" name="cardnumber" maxlength="12">&nbsp;&nbsp;&nbsp;
                    Expiration Date<span class="error">*</span>: <input type="text" name="expirydate" placeholder="MM/YY">
                    Security Code<span class="error">*</span>: <input type="password" name="securitycode" maxlength="3"></p>
            </div>
        </fieldset>
        <div style="text-align: center;"><button type="submit" class="btn btn-primary">Pay</button></div>
        </p>
    </fieldset>
</form>

<footer>
    Copyright © 2022 Yash Marakna | <a href="https://github.com/Aryesh-M/eCommerce-college" target="_blank">My <b>GitHub</b></a>
</footer>
<script>
    localStorage.removeItem('checkout_remaining');

    function showCard(flag) {
        let pFieldset = document.getElementById("online-payment");
        if (pFieldset.style.display === "none") {
            pFieldset.style.display = "block";
        } else {
            pFieldset.style.display = "none";
        }
    }

    let subTotal = document.getElementById('sub-total');
    let tax = document.getElementById('tax');
    let totalAmount = document.getElementById('total');
    let _subtotal = subTotal.innerText;
    let _tax = tax.innerText;
    let _totalamount = totalAmount.innerText;

    function count(flag, total) {
        let qtyInput = document.getElementById('qty');
        let decBtn = document.getElementById('dec');
        let incBtn = document.getElementById('inc');
        let qty = qtyInput.value;
        if (flag === 0) {
            decBtn.disabled = (qty < 2);
            incBtn.disabled = (qty >= total);
            if (qty < 1 || qty > total) {
                alert(`Quantity should be in the range[${1}-${total}]`);
                qtyInput.value = 1;
            }
        } else {
            let value = (flag == 1) ? --qty : ++qty;
            qtyInput.value = value;
            decBtn.disabled = (qty < 2);
            incBtn.disabled = (qty >= total);
        }
        let quantity = qtyInput.value;
        subTotal.innerText = (parseFloat(_subtotal) * quantity).toFixed(2);
        tax.innerText = (parseFloat(_tax) * quantity).toFixed(2);
        totalAmount.innerText = (parseFloat(tax.innerText) + parseFloat(subTotal.innerText)).toFixed(2);
        quantity = quantity ? parseInt(quantity) : 1;
        document.getElementById('gqty').value = quantity;
    }
</script>

</html>