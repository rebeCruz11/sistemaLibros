<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 2rem;
            width: 100%;
            max-width: 450px;
        }
        .btn-register {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            color: white;
            padding: 10px 0;
            width: 100%;
        }
        .btn-register:hover {
            background: linear-gradient(45deg, #5b0ea6, #1d5fd6);
            color: white;
        }
        .text-gradient {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2 class="text-center mb-4 text-gradient fw-bold">Crear Cuenta</h2>
            
            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errores as $error): ?>
                        <?= $error ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?= RUTA; ?>auth/registro" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-control" 
                            required
                            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                            title="Solo se permiten letras y espacios">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input 
                            type="text" 
                            name="apellido" 
                            id="apellido" 
                            class="form-control" 
                            required
                            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                            title="Solo se permiten letras y espacios">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" id="contrasena" class="form-control" required minlength="6">
                    <div class="form-text">La contraseña debe tener al menos 6 caracteres.</div>
                </div>

                <div class="mb-3">
                    <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-register mb-3">Registrarse</button>
            </form>
            
            <div class="text-center">
                <small>¿Ya tienes cuenta? <a href="<?= RUTA; ?>">Inicia sesión aquí</a></small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Validación y bloqueo de números en tiempo real
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })

        // Bloquear números en tiempo real para nombre y apellido
        document.querySelectorAll("#nombre, #apellido").forEach(input => {
            input.addEventListener("input", function() {
                this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, "");
            });
        });

        // Validar que las contraseñas coincidan
        const contrasena = document.getElementById('contrasena');
        const confirmarContrasena = document.getElementById('confirmar_contrasena');

        function validarContrasenas() {
            if (contrasena.value !== confirmarContrasena.value) {
                confirmarContrasena.setCustomValidity('Las contraseñas no coinciden');
            } else {
                confirmarContrasena.setCustomValidity('');
            }
        }

        contrasena.addEventListener('change', validarContrasenas);
        confirmarContrasena.addEventListener('keyup', validarContrasenas);
    })();
    </script>
</body>
</html>