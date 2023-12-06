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

<body>
<?php
        session_start();
        if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        $servername = "localhost";
        $user = "root";
        $password = "";
        $dbName = "smartagro";
        $conn = new mysqli($servername, $user, $password, $dbName);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            echo $username;
            $estaciones = "SELECT fk_estacion FROM estacion_usuario WHERE fk_usuario = '" . $username . "'";
            $result1 = $conn->query($estaciones);
            
            echo "<form method='post'>";
            while ($row = $result1->fetch_assoc()) {
                echo "<input type='checkbox' name='estac[]' value='" . $row['fk_estacion'] . "'>" . "<br>";
                echo $row['fk_estacion'];
            }
            echo "<input type='submit' value='Submit' name='estacion'>";
            echo "</form>";
            

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $estacion = $_POST['estacion'];
            $query = "SELECT AVG(valor) as humedad_suelo, DATE(hora) as dia 
                      FROM hsuelo , sensores
                      WHERE hsuelo.fk_id_sensor=sensores.id_sensor 
                      AND sensores.fk_estacion = '". $estacion ."'
                      GROUP BY dia";
            
            $result = $conn->query($query);
            $jsonArray = array();
            
            // Check if there is any data returned by the SQL Query
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data = array(
                        "label" => $row["dia"],
                        "value" => $row["humedad_suelo"]
                    );
                    array_push($jsonArray, $data);
                }
                $conn->close();
            }
        }
        }
        }
?>
 <script type="text/javascript">
 const chartConfigs = {
                type: "column2d",
                width: "700",
                height: "400",
                dataFormat: "json",
                dataSource: {
                    // Chart Configuration
                    "chart": {
                        "caption": "Countries With Most Oil Reserves [2017-18]",
                        "subCaption": "In MMbbl = One Million barrels",
                        "xAxisName": "Country",
                        "yAxisName": "Reserves (MMbbl)",
                        "numberSuffix": "K",
                        "theme": "fusion",
                    },
                    // Chart Data
                    "data": data
                }
            }
            // Create a chart container
            $('document').ready(function () {
                $("#chart-container").insertFusionCharts(chartConfigs);
            });
        </script>
        <div id="chart-container">FusionCharts will render here</div>
</body>
</html>
