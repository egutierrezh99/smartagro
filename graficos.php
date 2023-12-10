<!DOCTYPE html>
<html lang="es">
<head>
 <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- FusionCharts -->
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    <!-- jQuery-FusionCharts -->
    <script type="text/javascript" src="https://rawgit.com/fusioncharts/fusioncharts-jquery-plugin/develop/dist/fusioncharts.jqueryplugin.min.js"></script>
    <!-- Fusion Theme -->
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <meta charset="UTF-8">
    <title>SMARTAGRO</title>
</head>
<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    require('db.php');
    
    echo $_SESSION['username'];
    
    // Form for selecting stations
    echo "<form method='post'>";
    echo "<label for='estacion'>Selecciona una estación:</label>";
    echo "<select id='estacion' name='estacion'>";
    $estaciones = "SELECT fk_estacion FROM estacion_usuario WHERE fk_usuario = '" . $username . "'";
    $result1 = $conn->query($estaciones);
    while ($row = $result1->fetch_assoc()) {
        echo "<option value='" . $row['fk_estacion'] . "'>" . $row['fk_estacion'] . "</option>";
    }
    echo "</select>";
    echo "<input type='submit' value='Submit' name='submit'>";
    echo "</form>";
    
    // Form for selecting sensors (outside of the first form)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $estacion = $_POST['estacion'];
        $_SESSION['estacion'] = $estacion;
        echo $estacion;
      
        echo "<form method='post'>";
        echo "<label for='sensor'>Selecciona el sensor que quiere monitorear:</label>";
        echo "<select id='sensor' name='sensor'>";
        $sensores = "SELECT tipo FROM sensores WHERE fk_estacion ='" . $estacion . "'";
        $result2 = $conn->query($sensores);
        while ($row = $result2->fetch_assoc()) {
            echo "<option value='" . $row['tipo'] . "'>" . $row['tipo'] . "</option>";
        }
        echo "</select>";
        /*echo "<input type='submit' value='Submit' name='submit2'>";*/
        echo "<label for='modo'>Selecciona la franja que se quiere monitorear:</label>";
        echo "<select id='modo' name='modo'>";
        echo "<option value='detallado'>Detallado</option>";
        echo "<option value='diario'>Diario</option>";
        echo "<option value='mensual'>Mensual</option>";
        echo "<option value='anual'>Anual</option>";
        echo "</select>";
        echo "<input type='submit' value='Submit' name='submit3'>";
        echo "</form>";
    }
    
    // Process the second form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit3'])) {
        echo "Form 2 submitted!";
        $estacion= $_SESSION['estacion'];
        $sensor= $_POST['sensor'];
        $_SESSION['sensor']=$sensor;
        $modo=$_POST['modo'];
        echo $modo;
            if($sensor=='suelo' && $modo=='diario'){
                $mfecha="dia";
                $titulo="Media de Humedad en el suelo por día";
                $variable='humedad_suelo';
                $unidad="%";
                $query = "SELECT AVG(hsuelo.valor) as humedad_suelo, DATE(hsuelo.hora) as dia
                      FROM hsuelo , sensores
                      WHERE hsuelo.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY DATE(hsuelo.hora)";
            }elseif($sensor=='temperatura' && $modo=='diario'){
                $mfecha="dia";
                $titulo="Media de Temperatura por día";
                $variable='temperatura';
                $unidad="ºC";
                $query = "SELECT AVG(temperatura.valor) as temperatura, DATE(temperatura.hora) as dia
                      FROM temperatura , sensores
                      WHERE temperatura.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY DATE(temperatura.hora)";
            }elseif($sensor=='humedad' && $modo=='diario'){
                $mfecha="dia";
                $titulo="Media de Humedad por día";
                $unidad="%";
                $variable='humedad';
                $query = "SELECT AVG(humedad.valor) as humedad, DATE(humedad.hora) as dia
                      FROM humedad , sensores
                      WHERE humedad.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY DATE(humedad.hora)";
            }elseif($sensor=='suelo' && $modo=='mensual'){
                $mfecha="mes";
                $titulo="Media de Humedad en el suelo por mes";
                $variable='humedad_suelo';
                $unidad="%";
                $query = "SELECT AVG(hsuelo.valor) as humedad_suelo, CONCAT(YEAR(hsuelo.hora), ' ', MONTHNAME(hsuelo.hora)) as mes
                      FROM hsuelo , sensores
                      WHERE hsuelo.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY CONCAT(YEAR(hsuelo.hora), ' ', MONTHNAME(hsuelo.hora))";
            }elseif($sensor=='temperatura' && $modo=='mensual'){
                $mfecha="mes";
                $titulo="Media de Temperatura por mes";
                $variable='temperatura';
                $unidad="ºC";
                $query = "SELECT AVG(temperatura.valor) as temperatura, CONCAT(YEAR(temperatura.hora), ' ', MONTHNAME(temperatura.hora)) as mes
                      FROM temperatura , sensores
                      WHERE temperatura.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY CONCAT(YEAR(temperatura.hora), ' ', MONTHNAME(temperatura.hora))";
            }elseif($sensor=='humedad' && $modo=='mensual'){
                $mfecha="mes";
                $titulo="Media de Humedad por mes";
                $unidad="%";
                $variable='humedad';
                $query = "SELECT AVG(humedad.valor) as humedad, CONCAT(YEAR(humedad.hora), ' ', MONTHNAME(humedad.hora)) as mes
                      FROM humedad , sensores
                      WHERE humedad.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY CONCAT(YEAR(humedad.hora), ' ', MONTHNAME(humedad.hora))";
            }elseif($sensor=='suelo' && $modo=='anual'){
                $mfecha="anio";
                $titulo="Media de Humedad en el suelo por año";
                $variable='humedad_suelo';
                $unidad="%";
                $query = "SELECT AVG(hsuelo.valor) as humedad_suelo, YEAR(hsuelo.hora) as anio
                      FROM hsuelo , sensores
                      WHERE hsuelo.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY YEAR(hsuelo.hora)";
            }elseif($sensor=='temperatura' && $modo=='anual'){
                $mfecha="anio";
                $titulo="Media de Temperatura por año";
                $variable='temperatura';
                $unidad="ºC";
                $query = "SELECT AVG(temperatura.valor) as temperatura, YEAR(temperatura.hora) as anio
                      FROM temperatura , sensores
                      WHERE temperatura.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY YEAR(temperatura.hora)";
            }elseif($sensor=='humedad' && $modo=='anual'){
                $mfecha="anio";
                $titulo="Media de Humedad por año";
                $unidad="%";
                $variable='humedad';
                $query = "SELECT AVG(humedad.valor) as humedad, YEAR(humedad.hora) as anio
                      FROM humedad , sensores
                      WHERE humedad.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY YEAR(humedad.hora)";
            }elseif($sensor=='suelo' && $modo=='detallado'){
                $mfecha="hora";
                $titulo="Media de Humedad en el suelo por hora";
                $variable='humedad_suelo';
                $unidad="%";
                $query = "SELECT AVG(hsuelo.valor) as humedad_suelo, DATE_FORMAT(hsuelo.hora, '%Y-%m-%d %H:00:00') as hora
                      FROM hsuelo , sensores
                      WHERE hsuelo.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY DATE_FORMAT(hsuelo.hora, '%Y-%m-%d %H:00:00')";
            }elseif($sensor=='temperatura' && $modo=='detallado'){
                $mfecha="hora";
                $titulo="Media de Temperatura por hora";
                $variable='temperatura';
                $unidad="ºC";
                $query = "SELECT AVG(temperatura.valor) as temperatura, DATE_FORMAT(temperatura.hora, '%Y-%m-%d %H:00:00') as hora
                      FROM temperatura , sensores
                      WHERE temperatura.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY DATE_FORMAT(temperatura.hora, '%Y-%m-%d %H:00:00')";
            }elseif($sensor=='humedad' && $modo=='detallado'){
                $mfecha="hora";
                $titulo="Media de Humedad por hora";
                $unidad="%";
                $variable='humedad';
                $query = "SELECT AVG(humedad.valor) as humedad, DATE_FORMAT(humedad.hora, '%Y-%m-%d %H:00:00') as hora
                      FROM humedad , sensores
                      WHERE humedad.fk_id_sensor=sensores.id_sensor
                      AND sensores.fk_estacion='" . $estacion . "'
                      GROUP BY DATE_FORMAT(humedad.hora, '%Y-%m-%d %H:00:00')";
            }
           
        $result = $conn->query($query);
        $jsonArray = array();
        
        // Check if there is any data returned by the SQL Query
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = array(
                    "label" => $row[$mfecha],
                    "value" => $row[$variable]
                );
                array_push($jsonArray, $data);
            }
        }
        }
    }
    
    // Close the connection to the database
    $conn->close();


?>
<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("estacion");
        if (dropdown.style.display === '' || dropdown.style.display === 'none') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }
</script>
<script type="text/javascript">
    // Utiliza el array PHP $jsonArray para configurar los datos del gráfico
    const data = <?php echo json_encode($jsonArray); ?>;
    const chartConfigs = {
        type: "column2d",
        width: "700",
        height: "400",
        dataFormat: "json",
        dataSource: {
            // Configuración del gráfico
            "chart": {
                "caption": "<?php echo htmlspecialchars($titulo); ?>",
                "subCaption": "Promedio diario",
                "xAxisName": "Día",
                "yAxisName": "<?php echo htmlspecialchars($variable); ?>",
                "numberSuffix": "<?php echo htmlspecialchars($unidad); ?>",
                "theme": "fusion",
            },
            // Datos del gráfico
            "data": data
        }
    };

    // Crear un contenedor de gráfico
    $(document).ready(function () {
        $("#chart-container").insertFusionCharts(chartConfigs);
    });
</script>
<div id="chart-container">FusionCharts will render here</div>
</body>
</html>
