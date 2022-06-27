<link rel="stylesheet" href="./css/styles.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">

<?php
require('./mysqli_oop_connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Books list</title>
    <style>
        body {
            background-color: #fce4ec;
        }

        img {
            height: 200px;
            width: 200px;
        }

        h1 {
            text-align: center;
        }

        .book-title {
            font-size: 1.5em;
            font-weight: 600;
        }

        .author {
            font-size: 1.1em;
        }

        .price::before {
            content: "$";
        }

        .price {
            color: #ff00b4;
            font-size: 1.3em;
            font-weight: bold;
        }

        .books-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .book-item {
            margin: 0.5em;
            border: 3px solid #90caf9;
            padding: 1em;
            text-align: center;
            background-color: #1976D2;
            border-radius: 20px;
            color: white;
        }

        .total {
            color: black;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .total>span {
            color: white;
            font-size: 1.5em;
        }
    </style>
</head>

<body>
    <h2 class="text-center"><img onclick="window.location.href = 'index.php'" class="logo" src="images/logo.png" alt="Bookstore Logo"></h2>
    <div>
        <div class="books-wrapper">
            <?php
            $q = 'SELECT * FROM `bookstore`.`bookinventory`';
            $result = $mysqli->query($q);
            if ($mysqli->error) {
                echo $mysqli->error;
            }
            $count = mysqli_num_rows($result);
            if ($count > 0) {
                while ($row = $result->fetch_object()) {
                    $jsonobj = json_encode($row);
                    echo $row->quantity > 0 ? "" : "<div class='disable' title='Out of stock'>";
                    echo "<a href='javascript:void(0)' onclick='checkout($row->quantity > 0 ? $jsonobj : 0)'><div class='book-item' title='Checkout'>
                    <img src='https://via.placeholder.com/150' alt='Image Placeholder' />
                    <p class='book-title'>$row->book_title</p>
                    <p class='author' title='Author'>$row->author</p>
                    <p class='price' title='Price'>$row->price</p>
                    <p class='total'>Total items :&nbsp;&nbsp <span title='Remaining Items'>$row->quantity</span></p>
                    <p class='error'><span title='Out of stock'>Out of Stock</span></p>
                    </div></a>";
                    echo $row->quantity > 0 ? "" : "</div>";
                }
            } else {
                echo "<div style='margin-left:40%;color:orange;'><h1>Oops! no books found</h1></div>";
            }
            ?>
        </div>

    </div>
    <footer>
        Copyright © 2022 Yash Marakna | <a href="https://github.com/Aryesh-M/eCommerce-college" target="_blank">My <b>GitHub</b></a>
    </footer>
    <script>
        function checkout(data) {
            if (!data) {
                return true;
            }
            document.cookie = "product_id=" + data.id;
            let value = ('; ' + document.cookie).split(`; is_loggedin=`).pop().split(';')[0];
            if (value) {
                localStorage.setItem('checkout_remaining', false);
                window.location.href = './checkout.php';
            } else {
                let result = confirm("Checkout requires you to log in. Proceed to LOGIN?");
                if (result) {
                    localStorage.setItem('checkout_remaining', true);
                    window.location.href = './login.php';
                }
            }
        }
    </script>
</body>

</html>