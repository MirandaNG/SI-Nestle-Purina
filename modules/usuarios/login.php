<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);    
    session_start();
    include '../../config/conexion.php';
    include '../../includes/header-01.php';
    include '../../functions/usuarios_functions.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = $_POST['usuario'];
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // Usar la función de validación de login
        $validacion = validar_login($usuario, $password, $conexion);

        if (is_numeric($validacion)) {
            // Si la validación fue exitosa, almacenar el ID de usuario en la sesión
            $_SESSION['usuario_id'] = $validacion;
            $_SESSION['usuario'] = $usuario;
            
            // Obtener el rol del usuario
            $query_rol = "SELECT rol_id FROM usuarios WHERE usu_nombre = ?";
            $stmt_rol = $conexion->prepare($query_rol);
            $stmt_rol->bind_param('s', $usuario);
            $stmt_rol->execute();
            $resultado_rol = $stmt_rol->get_result();
            $datosUsuario = $resultado_rol->fetch_assoc();
            $_SESSION['rol_id'] = $datosUsuario['rol_id'];

            // Redirigir al panel
            header('Location: panel.php');
            exit();
        } else {
            // Si hubo un error en la validación
            $error = $validacion;
        }
    }
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <h3 class="text-center mb-4 title-login">Inicio de Sesión</h3>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" required name="usuario">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" required name="password">
                </div>
                <button type="submit" class="btn w-100 btn-login">Iniciar Sesión</button>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger mt-3">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <!-- Enlace para registrarse -->
                <div class="mt-3 text-center">
                    <p>
                        ¿No tienes cuenta?
                        <a href="sign-in.php" class="text-decoration-none"> Regístrate aquí</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>