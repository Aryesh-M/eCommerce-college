<link rel="stylesheet" href="./css/styles.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">

<?php
$isLoggedIn = 0;
$cookieName = 'is_loggedin';
if (isset($_COOKIE[$cookieName])) {
  $isLoggedIn = $_COOKIE[$cookieName];
}

echo "";
echo $isLoggedIn ? ("<div class='login-section'>
    <button id='login' class='btn btn-danger' onclick='logout()'>
      Logout
    </button>
  </div>") : ("<div class='login-section'><button class='btn btn-primary' id='login' onclick='window.location.href = `./login.php`'>Login</button></div>");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
  <title>BookStore</title>
  <style>
    body {
      /* Photo by Janko Ferlic: https://www.pexels.com/photo/light-inside-library-590493/ */
      background-image: url('images/bookstore.jpg');
    }

    a {
      text-decoration: none;
      font-size: 1.1em;
    }

    button {
      color: white !important;
      border: 3px solid white !important;
    }

    button:hover {
      transform: scale(1.1);
    }

    .login-section {
      text-align: right;
      padding: 1em 1em;
    }

    #login {
      width: 150px;
    }

    #logout {
      width: 150px;
    }

    .to-books {
      padding: 10%;
    }

    .to-books>button {
      height: 4em;
      width: 10em;
      border: 3px solid white !important;
    }

    footer {
      color: white;
    }
  </style>
</head>

<body>
  <h2 class="text-center"><img onclick="window.location.href = 'index.php'" class="logo" src="images/logo.png" alt="Bookstore Logo"></h2>
  <div class="text-center to-books">
    <button class="btn btn-success" onclick="window.location.href = 'bookStore.php'">Explore books</button><br /><br />
  </div>
  <footer>
    Copyright © 2022 Yash Marakna | <a href="https://github.com/Aryesh-M/eCommerce-college" target="_blank">My <b>GitHub</b></a>
  </footer>
  <script>
    function logout() {
      document.cookie = 'is_loggedin' + '=; Max-Age=0';
      document.cookie = 'user_id' + '=; Max-Age=0';
      document.cookie = 'product_id' + '=; Max-Age=0';
      window.location.reload();
    }
  </script>
</body>

</html>