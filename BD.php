<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ERES GENIAL</title>
</head>
<body>
    <?php
    $enlace = mysqli_connect("localhost", "root", "", "alumnos");

     if (!$enlace){
    die("no pudo conectarse a la base de datos" . mysqli_error($enlace)); // Se agrega $enlace como argumento
    }
     echo "ERES GENIAL SI CONECTO";
     mysqli_close($enlace);
     ?>
</body>
</html>
