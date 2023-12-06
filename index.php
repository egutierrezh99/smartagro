<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SMARTAGRO</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5"> <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>SMARTAGRO</h1>
    <p>
        <a href="login.php" class="btn btn-warning">Iniciar Sesión</a>
        <a href="informacion.php" class="btn btn-danger ml-3">Información</a>
    </p>



<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "smartagro";

$conn = new mysqli($servername, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    session_start();
    echo "la sesion se ha abierto ";

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: graficos.php");
        exit();
    } else {
    echo "no se esta loggeado";
    }
}
?>
</body>
</html>