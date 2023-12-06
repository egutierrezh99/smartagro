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
    }

    echo $username;
    $estaciones = "SELECT fk_estacion FROM estacion_usuario WHERE fk_usuario = '" . $username . "'";
    $result1 = $conn->query($estaciones);

    echo "<form method='post'>";
    echo "<label for='estacion'>Selecciona una estación:</label>";
    echo "<button type='button' onclick='toggleDropdown()'>Mostrar/ocultar estaciones</button>";
    echo "<select id='estacion' name='estacion[]' multiple style='display: none;'>"; // Inicialmente oculto
    while ($row = $result1->fetch_assoc()) {
        echo "<option value='" . $row['fk_estacion'] . "'>" . $row['fk_estacion'] . "</option>";
    }
    echo "</select>";
    echo "<input type='submit' value='Submit' name='submit'>";
    echo "</form>";

    // Cierre de sesión
    echo "<form method='post'>";
    echo "<input type='submit' name='logout' value='Cerrar Sesión'>";
    echo "</form>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['submit'])) {
            $estacion = $_POST['estacion'];
            $query = "SELECT AVG(valor) as humedad_suelo, DATE(hora) as dia 
                      FROM hsuelo , sensores
                      WHERE hsuelo.fk_id_sensor=sensores.id_sensor 
                      AND sensores.fk_estacion IN ('" . implode("','", $estacion) . "')
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
            }
        }

        // Cerrar sesión
        if (isset($_POST['logout'])) {
            // Cerrar sesión y redirigir a index.php
            header("Location: index.php");
            session_destroy();
            
            exit();
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
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
                "caption": "Humedad del suelo por estación",
                "subCaption": "Promedio diario",
                "xAxisName": "Día",
                "yAxisName": "Humedad del suelo",
                "numberSuffix": "%",
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
