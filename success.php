<link rel="stylesheet" href="./css/styles.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">

<?php
echo "<div class='order-heading'><h3>Your order is successful.</h3>
<p>You will be redirected to home page...</p></div>";
?>
<style>
    .order-heading {
        text-align: center;
        margin-top: 8em;
    }

    .order-heading>h3 {
        color: green;
        font-size: 3em;
    }

    .order-heading>p {
        color: gray;
        font-size: 2em;
    }
</style>
<script>
    let is_order_placed = localStorage.getItem('order_checkout');
    localStorage.removeItem('order_checkout');
    if (is_order_placed) {
        const timer = setTimeout(redirectToHome, 3000);

        function redirectToHome() {
            clearTimeout(timer);
            window.location.href = './index.php';
        }
    } else {
        window.location.href = './index.php';
    }
</script>