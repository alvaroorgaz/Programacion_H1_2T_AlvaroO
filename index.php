<?php
$host = "localhost";
$port = 3306;
$socket = "";
$user = "root";
$password = "root";
$dbname = "StreamWeb";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
    or die('Could not connect to the database server' . mysqli_connect_error());

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user_id'])) {
    $user_id = $_POST['delete_user_id'];
    $sql_delete = "DELETE FROM Usuarios WHERE id = $user_id";
    if ($con->query($sql_delete) === TRUE) {
        echo "<script>alert('Usuario eliminado exitosamente');</script>";
    } else {
        echo "<script>alert('Error al eliminar el usuario: " . addslashes($con->error) . "');</script>";
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
    <script>
        function redirectToCreateUser() {
            window.location.href = 'create_user.php';
        }
    </script>
</head>
<body>
    <h1>Usuarios</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Edad</th>
            <th>Plan Base</th>
            <th>Paquete Adicional</th>
            <th>Duración Suscripción</th>
            <th>Precio Paquete</th>
            <th>Precio Plan</th>
            <th>Coste Mensual</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT usuarios.id, usuarios.nombre, usuarios.apellidos, usuarios.correo, usuarios.edad, 
                       usuarios.plan_base, paquetes.nombre AS paquete_adicional, usuarios.duracion_suscripcion, 
                       paquetes.precio AS precio_paquete, planes.precio AS precio_plan, 
                       (paquetes.precio + planes.precio) AS coste_mensual
                FROM usuarios
                LEFT JOIN suscripciones ON usuarios.id = suscripciones.usuario_id
                LEFT JOIN paquetes ON suscripciones.paquete_id = paquetes.id
                LEFT JOIN planes ON usuarios.plan_base = planes.nombre";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["nombre"] . "</td>
                        <td>" . $row["apellidos"] . "</td>
                        <td>" . $row["correo"] . "</td>
                        <td>" . $row["edad"] . "</td>
                        <td>" . $row["plan_base"] . "</td>
                        <td>" . $row["paquete_adicional"] . "</td>
                        <td>" . $row["duracion_suscripcion"] . "</td>
                        <td>" . $row["precio_paquete"] . "€</td>
                        <td>" . $row["precio_plan"] . "€</td>
                        <td>" . $row["coste_mensual"] . "€</td>
                        <td>
                            <form method='get' action='edit_user.php'>
                                <input type='hidden' name='edit_user_id' value='" . $row["id"] . "'>
                                <input type='submit' value='Editar' id='editar'>
                            </form>
                            <form method='post' action='index.php' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");'>
                                <input type='hidden' name='delete_user_id' value='" . $row["id"] . "'>
                                <input type='submit' value='Eliminar' id='eliminar'>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No hay datos disponibles</td></tr>";
        }
        ?>
        <tr><td colspan='12'><input id="crear" type="submit" value="Crear Usuario" onclick="redirectToCreateUser()"></td></tr>    

    </table>
    <!-- Información de planes -->
    <h2>Información de Planes</h2>
        <br>
        <table>
            <tr>
                <th>Pack</th>
                <th>Precio Mensual €</th>
            </tr>
            <?php
            $sql = "SELECT nombre, precio FROM Paquetes";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["nombre"] . "</td><td>" . $row["precio"] . "€</td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No hay datos disponibles</td></tr>";
            }
            ?>
        </table>
        <br>
    <table>
        <tr>
            <th>Plan</th>
            <th>Precio Mensual €</th>
        </tr>
        <?php
        $sql = "SELECT nombre, precio FROM Planes";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["nombre"] . "</td><td>" . $row["precio"] . "€</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No hay datos disponibles</td></tr>";
        }
        ?>
    </table>
    <br>
</body>
</html>