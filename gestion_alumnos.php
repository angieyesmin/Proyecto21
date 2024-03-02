<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERES GENIAL</title>
    <style>
        body {
            background-color: #7FFFD4; 
            font-family: Arial, sans-serif; 
            color: #333; 
            text-align: center; 
            padding-top: 20px; 
        }

        h2 {
            color: #008080; 
        }

        input[type="text"],
        input[type="number"],
        input[type="submit"] {
            padding: 10px; 
            font-size: 16px; 
            border: none; 
            background-color: #fff; 
            color: #000; 
            cursor: pointer; 
            border-radius: 20px; 
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2); 
            margin-bottom: 10px; 
        }

        input[type="submit"]:hover {
            background-color: #f0f0f0; 
        }

        ul {
            list-style-type: none; 
            padding: 0;
            text-align: left; 
            margin: auto; 
            max-width: 300px; 
        }

        ul li {
            margin-bottom: 10px; 
        }
    </style>
</head>
<body>

    <?php
    // Conexión a la base de datos
    $enlace = mysqli_connect("localhost", "root", "", "alumnos");
    
    // Verificar si la conexión fue exitosa
    if (!$enlace) {
        die("No se pudo conectar a la base de datos: " . mysqli_connect_error());
    }

    // Verificar si se ha enviado el formulario para agregar un nuevo alumno
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nuevo_alumno"]) && !empty($_POST["nuevo_alumno"])) {
        // Obtener el nombre del nuevo alumno
        $nuevo_nombre = $_POST["nuevo_alumno"];
        
        // Insertar el nuevo alumno en la base de datos
        $sql_insert = "INSERT INTO alumnos (nombre) VALUES ('$nuevo_nombre')";
        if (mysqli_query($enlace, $sql_insert)) {
            echo "Nuevo alumno agregado correctamente.";
        } else {
            echo "Error al agregar el nuevo alumno: " . mysqli_error($enlace);
        }
    }

    // Verificar si se ha enviado el formulario para ingresar la calificación de un alumno
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["alumno_calificacion"]) && isset($_POST["calificacion"]) && !empty($_POST["alumno_calificacion"])) {
        // Obtener el nombre del alumno y la calificación
        $alumno_calificacion = $_POST["alumno_calificacion"];
        $calificacion = $_POST["calificacion"];
        
        // Verificar si el alumno existe en la base de datos
        $sql_check_alumno = "SELECT nombre FROM alumnos WHERE nombre = '$alumno_calificacion'";
        $result_check_alumno = mysqli_query($enlace, $sql_check_alumno);
        if (mysqli_num_rows($result_check_alumno) > 0) {
            // Insertar la calificación del alumno en la base de datos
            $sql_insert_calificacion = "UPDATE alumnos SET calificacion = $calificacion WHERE nombre = '$alumno_calificacion'";
            if (mysqli_query($enlace, $sql_insert_calificacion)) {
                echo "Calificación agregada correctamente.";
            } else {
                echo "Error al agregar la calificación: " . mysqli_error($enlace);
            }
        } else {
            echo "No se encontró al alumno $alumno_calificacion en la base de datos.";
        }
    }

    // Verificar si se ha enviado el formulario para ver la calificación de un alumno
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_calificacion"]) && !empty($_POST["nombre_calificacion"])) {
        // Obtener el nombre del alumno
        $nombre_calificacion = $_POST["nombre_calificacion"];
        
        // Obtener la calificación del alumno de la base de datos
        $sql_select_calificacion = "SELECT calificacion FROM alumnos WHERE nombre = '$nombre_calificacion'";
        $result_calificacion = mysqli_query($enlace, $sql_select_calificacion);

        if ($result_calificacion) {
            if (mysqli_num_rows($result_calificacion) > 0) {
                $row_calificacion = mysqli_fetch_assoc($result_calificacion);
                echo "<h2>Calificación de $nombre_calificacion:</h2>";
                echo "<p>" . $row_calificacion["calificacion"] . "</p>";
            } else {
                echo "No se encontró al alumno $nombre_calificacion en la base de datos.";
            }
        } else {
            echo "Error al obtener la calificación: " . mysqli_error($enlace);
        }
    }

    // Mostrar la lista de alumnos en la base de datos ordenados por calificación de mayor a menor
    $sql_select_alumnos_calificacion = "SELECT nombre, calificacion FROM alumnos ORDER BY calificacion DESC";
    $result_alumnos_calificacion = mysqli_query($enlace, $sql_select_alumnos_calificacion);

    if (mysqli_num_rows($result_alumnos_calificacion) > 0) {
        echo "<h2>Lista de Alumnos Ordenados por Calificación (de mayor a menor):</h2>";
        echo "<ul>";
        while ($row_alumnos_calificacion = mysqli_fetch_assoc($result_alumnos_calificacion)) {
            echo "<li>" . $row_alumnos_calificacion["nombre"] . " - Calificación: " . $row_alumnos_calificacion["calificacion"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No hay alumnos en la base de datos.";
    }

    // Cerrar la conexión
    mysqli_close($enlace);
    ?>

    <!-- Formulario para agregar un nuevo alumno -->
    <h2>Agregar Nuevo Alumno:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nuevo_alumno">Nombre:</label>
        <input type="text" id="nuevo_alumno" name="nuevo_alumno" required>
        <input type="submit" value="Agregar">
    </form>

    <!-- Formulario para ingresar la calificación de un alumno -->
    <h2>Ingresar Calificación:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="alumno_calificacion">Nombre del Alumno:</label>
        <input type="text" id="alumno_calificacion" name="alumno_calificacion" required><br>
        <label for="calificacion">Calificación:</label>
        <input type="number" id="calificacion" name="calificacion" required><br>
        <input type="submit" value="Agregar Calificación">
    </form>

    <!-- Formulario para ver la calificación de un alumno -->
    <h2>Ver Calificación:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nombre_calificacion">Nombre del Alumno:</label>
        <input type="text" id="nombre_calificacion" name="nombre_calificacion" required><br>
        <input type="submit" value="Ver Calificación">
    </form>
</body>
</html>