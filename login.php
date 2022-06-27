<link rel="stylesheet" href="./css/styles.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">

<?php
$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('./mysqli_oop_connect.php');
    $email = trim(strip_tags($_POST['email']));
    $password = trim(strip_tags($_POST['password']));
    $is_empty = false;
    if (empty($email)) {
        $is_empty = true;
        $errors['email'] = '<span class="error">Username is mandatory.</span>';
    }
    if (empty($password)) {
        $is_empty = true;
        $errors['password'] = '<span class="error">Password is mandatory.</span>';
    }
    if (!$is_empty) {
        $q = "SELECT * FROM `bookstore`.`users` WHERE email=? AND password=?";
        $stmt = $mysqli->prepare($q);

        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $user = $result->fetch_assoc();
            setcookie("is_loggedin", true);
            setcookie("user_id", $user["id"]);
            echo "<script>
            let x = localStorage.getItem('checkout_remaining');
            x ?  window.location.href = './checkout.php' : window.location.href = './index.php'; 
            </script>";
        } else {
            $errors['wrong'] = '<p class="error">Wrong username and/or password!</p>';
        }
    }
} else {
    echo "<script>
    const value = ('; ' + document.cookie).split(`; is_loggedin=`).pop().split(';')[0];
    if (value) {
        history.back();
    }
</script>";
}
?>
<style>
    .error {
        color: red;
        text-align: center;
    }
</style>
<div style="text-align:center;margin: 7.2em auto">
    <h2 class="text-center"><img onclick="window.location.href = 'index.php'" class="logo" src="images/logo.png" alt="Bookstore Logo"></h2>
    <div class="user-form border border-primary rounded">
        <h1>Login</h1>
        <form action="login.php" method="POST">
            <?php if (array_key_exists("wrong", $errors)) echo $errors['wrong']; ?>
            <?php if (array_key_exists("email", $errors)) echo $errors['email']; ?>
            <p>Username<span class="error">*</span>: <input type="email" name="email" value="<?php if (isset($_POST['email'])) echo ($_POST['email']); ?>"></p>
            <?php if (array_key_exists("password", $errors)) echo $errors['password']; ?>
            <p>Password<span class="error">*</span>: <input type="password" name="password" value="<?php if (isset($_POST['password'])) echo ($_POST['password']); ?>"></p>
            <button class="btn btn-primary" style="width: 8em;margin-left: 1em;">Login</button>
            <p class="text-info">New user? <a href="./signup.php"><u>Create new account</u></a></p>
        </form>
    </div>
</div>
<footer>
    Copyright © 2022 Yash Marakna | <a href="https://github.com/Aryesh-M/eCommerce-college" target="_blank">My <b>GitHub</b></a>
</footer>