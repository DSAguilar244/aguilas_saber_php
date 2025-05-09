<?php
// Mensajes para procesamiento de login y recuperación de contraseña
$mensaje = $_GET['mensaje'] ?? '';
$tipo = $_GET['tipo'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - Aguilas del Saber</title>
  <link rel="icon" href="static/img/fondo_aguilas_saber.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="login-container p-4 shadow rounded-3">
      <div class="text-center mb-4">
        <img src="static/img/fondo_aguilas_saber.png" alt="Logo de la escuela" class="img-fluid">
      </div>
      <h2 class="text-center mb-4">Iniciar Sesión</h2>
      <form id="loginForm" action="procesar_login.php" method="POST">
        <div class="input-group mb-3">
          <input type="text" name="usuario" class="form-control" placeholder="Nombre de usuario" required>
          <span class="input-group-text"><i class="fas fa-user"></i></span>
        </div>              
        <div class="mb-3 position-relative">
          <input type="password" name="contraseña" id="contraseña" class="form-control" placeholder="Contraseña" required>
          <span id="toggle-password" class="position-absolute top-50 end-0 translate-middle-y pe-3" style="cursor: pointer;">
            <i class="fas fa-eye-slash"></i>
          </span>
        </div>
        <button type="submit" class="btn btn-danger w-100">Iniciar sesión</button>
      </form>
      <div class="footer text-center mt-3">
        <p><a href="#" data-bs-toggle="modal" data-bs-target="#recoverPasswordModal">¿Olvidaste tu contraseña?</a></p>
      </div>
    </div>
  </div>

  <!-- Modal Recuperar contraseña -->
  <div class="modal fade" id="recoverPasswordModal" tabindex="-1" aria-labelledby="recoverPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="recuperar_contraseña.php" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="recoverPasswordModalLabel">Recuperar Contraseña</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input type="email" name="email" class="form-control" placeholder="Ingresa tu correo" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Recuperar contraseña</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php if ($mensaje): ?>
  <div class="toast align-items-center text-bg-<?= $tipo ?> border-0 position-fixed bottom-0 end-0 m-3 show" role="alert">
    <div class="d-flex">
      <div class="toast-body"><?= htmlspecialchars($mensaje) ?></div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
  </div>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('toggle-password').addEventListener('click', function () {
      const passInput = document.getElementById('contraseña');
      const type = passInput.type === 'password' ? 'text' : 'password';
      passInput.type = type;
      this.innerHTML = type === 'password' ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    });
  </script>
</body>
</html>