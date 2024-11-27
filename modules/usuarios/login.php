<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);    
    session_start();
    include '../../config/conexion.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = $_POST['usuario'];
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // Buscar el usuario en la tabla Usuarios
        $query = 'SELECT * FROM Usuarios WHERE usu_nombre = ?';
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $datosUsuario = $resultado->fetch_assoc();
            // Verificar la contraseña si el campo no está vacío
            if (!empty($password) && password_verify($password, $datosUsuario['usu_contraseña'])) {
                // Guardar el usuario en la sesión
                $_SESSION['usuario'] = $datosUsuario['usu_nombre'];
                $_SESSION['rol_id'] = $datosUsuario['rol_id'];
                header('Location: panel.php');
                exit();
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Usuario no encontrado';
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <h3 class="text-center mb-4">Inicio de Sesión</h3>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" required name="usuario">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" required name="password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger mt-3">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Enlace para registrarse -->
                    <div class="mt-3 text-center">
                        <p>
                            ¿No tienes cuenta?
                            <a href="sign-in.php"> Regístrate aquí</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>