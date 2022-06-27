<link rel="stylesheet" href="./css/styles.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">
<?php
$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('./mysqli_oop_connect.php');
    $first_name = trim(strip_tags($_POST['fname']));
    $last_name = trim(strip_tags($_POST['lname']));
    $email = trim(strip_tags($_POST['uemail']));
    $password = trim(strip_tags($_POST['password']));
    $address = trim(strip_tags($_POST['address']));
    $is_empty = false;
    if (empty($first_name)) {
        $errors['fname'] = '<span class="error">First Name is mandatory.</span>';
        $is_empty = true;
    }
    if (empty($email)) {
        $is_empty = true;
        $errors['email'] = '<span class="error">Email is mandatory.</span>';
    }
    if (empty($password)) {
        $is_empty = true;
        $errors['password'] = '<span class="error">Password is mandatory.</span>';
    }
    if (!$is_empty) {
        //  check if user already exists
        $email = trim(strip_tags($_POST['uemail']));
        $q = "SELECT * FROM `bookstore`.`users` WHERE email=?";
        $st = $mysqli->prepare($q);

        $st->bind_param("s", $email);
        $st->execute();
        $result = $st->get_result();
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $errors['exists'] = '<p class="error">A User with entered Email already exists!</p>';
        } else {
            $fn = $mysqli->real_escape_string(trim($first_name));
            $ln = !empty($last_name) ? $mysqli->real_escape_string(trim($last_name)) : "NULL";
            $e = $mysqli->real_escape_string(trim($email));
            $p = $mysqli->real_escape_string(trim($password));
            $a = !empty($last_name) ? $mysqli->real_escape_string(trim($address)) : "NULL";
            $qry = "INSERT INTO `users`(`first_name`,`last_name`,`email`, `password`,`address`) VALUES (?,?,?,?,?)";
            $stmt = $mysqli->prepare($qry);
            $stmt->bind_param('sssss', $fn, $ln, $e, $p, $a);
            $u = trim($username);
            $p = trim($password);
            $stmt->execute();

            if ($stmt->affected_rows == 1) {
                echo "<script>window.location.href = './login.php';</script>";
            } else {
                echo "<h3 style='color:red'>Something went wrong!</h3>";
            }
            $stmt->close();
        }
    }
} else {
    echo "<script>
    const value = ('; ' + document.cookie).split(`; is_loggedin=`).pop().split(';')[0];
    if (value) {
        history.back()
    }
</script>";
}
?>
<html>

<head>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<div style="text-align:center;">
    <h2 class="text-center"><img onclick="window.location.href = 'index.php'" class="logo" src="images/logo.png" alt="Bookstore Logo"></h2>
    <div class="user-form border border-primary rounded">
        <h1>Sign Up</h1>
        <form action="signup.php" method="POST">
            <?php if (array_key_exists("exists", $errors)) echo $errors['exists']; ?>

            <?php if (array_key_exists("fname", $errors)) echo $errors['fname']; ?>
            <p>First Name<span class="error">*</span>: <span></span> <input type="text" name="fname" id="fname" value="<?php if (isset($_POST['fname'])) echo ($_POST['fname']); ?>"></p>
            <p>Last Name: <input type="text" name="lname" name="lname" value="<?php if (isset($_POST['lname'])) echo ($_POST['lname']); ?>"></p>
            <?php if (array_key_exists("email", $errors))  echo $errors['email']; ?>
            <p>User Email<span class="error">*</span>: <input type="email" name="uemail" name="uemail" value="<?php if (isset($_POST['uemail'])) echo ($_POST['uemail']); ?>"></p>
            <?php if (array_key_exists("password", $errors))  echo $errors['password']; ?>
            <p>Password<span class="error">*</span>: &nbsp;<input type="password" name="password" name="password" value="<?php if (isset($_POST['password'])) echo ($_POST['password']); ?>"></p>
            <p class="address">Address: &nbsp;<textarea name="address" rows="3" cols="22" value="<?php if (isset($_POST['address'])) echo ($_POST['address']); ?>"></textarea></p>
            <button class="btn btn-primary" style="width: 8em;margin-left: 1em;">Sign Up</button>
            <p class="text-info">Already have an account? <a href="./login.php"><u>Login</u></a></p>
        </form>
    </div>
</div>
<footer>
    Copyright © 2022 Yash Marakna | <a href="https://github.com/Aryesh-M/eCommerce-college" target="_blank">My <b>GitHub</b></a>
</footer>

</html>