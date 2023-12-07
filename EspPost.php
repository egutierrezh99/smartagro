<?php

include'db.php';

if ($conn) {
    echo "Conexion con base de datos exitosa! ";
    
    if(isset($_POST['temperatura'])) {
        $temperatura = $_POST['temperatura'];
        echo "Estación meteorológica";
        echo " Temperatura : ".$temperatura;
    }
 
    if(isset($_POST['humedad'])) { 
        $humedad = $_POST['humedad'];
        echo " humedad : ".$humedad;
        $fecha_actual = date("Y-m-d H:i:s");
        
        $consulta = "INSERT INTO temperatura(fk_estacion, hora, valor) VALUES ('1','$temperatura', '$fecha_actual')";
       // $consulta = "UPDATE DHT11 SET Temperatura='$temperatura',Humedad='$humedad' WHERE Id = 1";
        $resultado = mysqli_query($con, $consulta);
        if ($resultado){
            echo " Registo en base de datos OK! ";
        } else {
            echo " Falla! Registro BD";
        }
    }
    
    
} else {
    echo "Falla! conexion con Base de datos ";   
}


?>