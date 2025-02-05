<?php
$host = "localhost";
$port = 3306;
$socket = "";
$user = "root";
$password = "root";
$dbname = "StreamWeb";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
    or die('Could not connect to the database server' . mysqli_connect_error());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['edit_user_id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $edad = $_POST['edad'];
    $plan_base = $_POST['plan_base'];
    $paquetes_adicionales = $_POST['paquetes_adicionales'];
    $duracion_suscripcion = $_POST['duracion_suscripcion'];

    $sql_update = "UPDATE Usuarios SET nombre='$nombre', apellidos='$apellidos', correo='$correo', edad='$edad', plan_base='$plan_base', duracion_suscripcion='$duracion_suscripcion' WHERE id=$user_id";
    if ($con->query($sql_update) === TRUE) {
        $sql_update_suscripcion = "UPDATE Suscripciones SET paquete_id='$paquetes_adicionales' WHERE usuario_id=$user_id";
        if ($con->query($sql_update_suscripcion) === TRUE) {
            echo "<script>alert('Usuario actualizado exitosamente'); window.location.href='user_database.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar la suscripción: " . addslashes($con->error) . "');</script>";
        }
    } else {
        echo "<script>alert('Error al actualizar el usuario: " . addslashes($con->error) . "');</script>";
    }
} else {
    $user_id = $_GET['edit_user_id'];
    $sql = "SELECT * FROM Usuarios WHERE id = $user_id";
    $result = $con->query($sql);
    $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="es" styles="font-family: sans-serif;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/src/img/logo.ico">
    <link rel="stylesheet" href="src/styles.css">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form method="post" action="edit_user.php" class="form_usuario"> 
        <input type="hidden" name="edit_user_id" value="<?php echo $user['id']; ?>">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?php echo $user['nombre']; ?>" required><br>
        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo $user['apellidos']; ?>" required><br>
        <label for="correo">Correo Electrónico:</label><br>
        <input type="email" id="correo" name="correo" value="<?php echo $user['correo']; ?>" required><br>
        <label for="edad">Edad:</label><br>
        <input type="number" id="edad" name="edad" value="<?php echo $user['edad']; ?>" min="0" required><br>
        <label for="plan_base">Tipo de Plan Base:</label><br>
        <select id="plan_base" name="plan_base" required>
            <option value="Básico" <?php if ($user['plan_base'] == 'Básico') echo 'selected'; ?>>Básico</option>
            <option value="Estándar" <?php if ($user['plan_base'] == 'Estándar') echo 'selected'; ?>>Estándar</option>
            <option value="Premium" <?php if ($user['plan_base'] == 'Premium') echo 'selected'; ?>>Premium</option>
        </select><br>
        <label for="paquetes_adicionales">Paquetes Adicionales:</label><br>
        <select id="paquetes_adicionales" name="paquetes_adicionales" required>
            <option value="1">Deporte</option>
            <option value="2">Cine</option>
            <option value="3">Infantil</option>
        </select><br>
        <label for="duracion_suscripcion">Duración de la Suscripción:</label><br>
        <select id="duracion_suscripcion" name="duracion_suscripcion" required>
            <option value="Mensual" <?php if ($user['duracion_suscripcion'] == 'Mensual') echo 'selected'; ?>>Mensual</option>
            <option value="Anual" <?php if ($user['duracion_suscripcion'] == 'Anual') echo 'selected'; ?>>Anual</option>
        </select><br><br>
        <input type="submit" value="Editar Usuario" id='editar'>
    </form>
</body>
</html>