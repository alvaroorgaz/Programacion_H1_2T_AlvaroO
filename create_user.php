<?php
$host = "localhost";
$port = 3306;
$socket = "";
$user = "root";
$password = "root";
$dbname = "StreamWeb";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
    or die('Could not connect to the database server' . mysqli_connect_error());

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['correo'];
    $edad = $_POST['edad'];
    $plan_base = $_POST['plan_base'];
    $paquetes_adicionales = $_POST['paquetes_adicionales'];
    $duracion_suscripcion = $_POST['duracion_suscripcion'];

    // Validaciones
    if ($edad < 18 && $paquetes_adicionales != 3) { // Assuming 3 is the ID for 'Infantil'
        $error_message = "Los usuarios menores de 18 años solo pueden contratar el Pack Infantil.";
    } elseif ($plan_base == 'Básico') {
        $sql_check_paquetes = "SELECT COUNT(*) AS paquete_count FROM Suscripciones WHERE usuario_id = (SELECT MAX(id) FROM Usuarios)";
        $result_check_paquetes = $con->query($sql_check_paquetes);
        $row_check_paquetes = $result_check_paquetes->fetch_assoc();
        if ($row_check_paquetes['paquete_count'] > 1) {
            $error_message = "Los usuarios del Plan Básico solo pueden seleccionar un paquete adicional.";
        }
    } elseif ($paquetes_adicionales == 1 && $duracion_suscripcion != 'Anual') { // Assuming 1 is the ID for 'Deporte'
        $error_message = "El Pack Deporte solo puede ser contratado si la duración de la suscripción es de 1 año.";
    }

    if (empty($error_message)) {
        try {
            // Insertar usuario en la tabla Usuarios
            $sql = "INSERT INTO Usuarios (nombre, apellidos, correo, edad, plan_base, duracion_suscripcion) 
                    VALUES ('$nombre', '$apellidos', '$email', '$edad', '$plan_base', '$duracion_suscripcion')";
            if ($con->query($sql) === TRUE) {
                $usuario_id = $con->insert_id; // Obtener el ID del usuario recién insertado

                // Insertar suscripción en la tabla Suscripciones
                $sql_suscripcion = "INSERT INTO Suscripciones (usuario_id, paquete_id) 
                                    VALUES ('$usuario_id', '$paquetes_adicionales')";
                if ($con->query($sql_suscripcion) === TRUE) {
                    echo "<script>alert('Nuevo usuario y suscripción creados exitosamente');</script>";
                } else {
                    throw new mysqli_sql_exception($con->error);
                }
            } else {
                throw new mysqli_sql_exception($con->error);
            }
        } catch (mysqli_sql_exception $e) {
            $error_message = $e->getMessage();
            echo "<script>alert('Error: " . addslashes($error_message) . "');</script>";
        }
    } else {
        echo "<script>alert('Error: " . addslashes($error_message) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es" styles="font-family: sans-serif;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/src/img/logo.ico">
    <link rel="stylesheet" href="src/styles.css">
    <title>StreamWeb</title>
</head>
<body>
    <h1>Crear Usuario</h1>
    <form method="post" action="create_user.php" class="form_usuario">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" required><br>
        <label for="correo">Correo Electrónico:</label><br>
        <input type="email" id="correo" name="correo" required><br>
        <label for="edad">Edad:</label><br>
        <input type="number" id="edad" name="edad" min="0" required><br>
        <label for="plan_base">Tipo de Plan Base:</label><br>
        <select id="plan_base" name="plan_base" required>
            <option value="Básico">Básico</option>
            <option value="Estándar">Estándar</option>
            <option value="Premium">Premium</option>
        </select><br>
        <label for="paquetes_adicionales">Paquetes Adicionales:</label><br>
        <select id="paquetes_adicionales" name="paquetes_adicionales" required>
            <option value="1">Deporte</option>
            <option value="2">Cine</option>
            <option value="3">Infantil</option>
        </select><br>
        <label for="duracion_suscripcion">Duración de la Suscripción:</label><br>
        <select id="duracion_suscripcion" name="duracion_suscripcion" required>
            <option value="Mensual">Mensual</option>
            <option value="Anual">Anual</option>
        </select><br><br>
        <input type="submit" value="Crear Usuario" id="crear">
        <input type="button" value="Volver" id="volver" onclick="window.location.href='index.php';">
    </form>
</body>
</html>