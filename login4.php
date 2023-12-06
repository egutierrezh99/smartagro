<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
session_start(); // Inicia la sesión

require('db.php');

// Si el formulario es enviado, inserta los valores en la base de datos.
if (isset($_POST['username'])){
    // Elimina las barras invertidas
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    // Verifica si el usuario existe en la base de datos.
    $query = "SELECT * FROM `usuario` WHERE nombre='". $username . "' and contra='". $password ."'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $rows = mysqli_num_rows($result);

    if($rows == 1){
        $_SESSION['username'] = $username;
        // Redirige al usuario a index.php
        header("location:graficos_def.php?user=$username");
        exit();
    } else {
        echo "<div class='form'>
            <h3>Username/password is incorrect.</h3>
            <br/>Click here to <a href='login.php'>Login</a>
        </div>";
    }
} else {
?>
    <div class="form">
        <h1>Log In</h1>
        <form action="" method="post" name="login">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <input name="submit" type="submit" value="Login" />
        </form>
    </div>
<?php } ?>
</body>
</html>
