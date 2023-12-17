<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="estilo.css" />
</head>
<body>
<div class="login-container">
<?php
session_start();
require('db.php');

if (isset($_POST['submit'])) {
    $username = stripslashes($_REQUEST['username']);
    $password = stripslashes($_REQUEST['password']);
    $query = "SELECT * FROM `usuario` WHERE nombre='" . $username . "' AND contra='" . $password . "'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $rows = mysqli_num_rows($result);
    if ($rows == 1) {
        $_SESSION['username'] = $username;
        header("Location: graficos.php");
        exit();
    } else {
        echo "<div class='form'>";
        echo "<h3>Username/password is incorrect.</h3>";
        echo "<br/>Click here to <a href='login.php'>Login</a></div>";
    }
} else {
?>
    <div class="form">
        <h1>Inicio de sesión</h1>
        <form action="" method="post" name="login">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <input name="submit" type="submit" value="Login" />
        </form>
    </div>
<?php } ?>
</div>
</body>
</html>
