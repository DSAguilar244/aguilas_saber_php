<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Aguilas del Saber</title>
    <link rel="icon" href="{{ asset('static/img/fondo_aguilas_saber.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="login-container p-4 shadow rounded-3">
        <div class="text-center mb-4">
            <img src="{{ asset('static/img/fondo_aguilas_saber.png') }}" alt="Logo de la escuela" class="img-fluid">
        </div>
        <h2 class="text-center mb-4">Iniciar Sesión</h2>

        {{-- Mensajes de sesión y errores --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" name="password" id="contraseña" class="form-control" placeholder="Contraseña" required>
                <span id="toggle-password" class="position-absolute top-50 end-0 translate-middle-y pe-3" style="cursor: pointer;">
                    <i class="fas fa-eye-slash"></i>
                </span>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
                <label class="form-check-label" for="remember_me">Recordarme</label>
            </div>
            <button type="submit" class="btn btn-danger w-100">Iniciar sesión</button>
        </form>
        <div class="footer text-center mt-3">
            <p>
                <a href="#" data-bs-toggle="modal" data-bs-target="#recoverPasswordModal">¿Olvidaste tu contraseña?</a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Recuperar contraseña -->
<div class="modal fade" id="recoverPasswordModal" tabindex="-1" aria-labelledby="recoverPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="recoverPasswordModalLabel">Recuperar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recover_email" class="form-label">Correo electrónico</label>
                        <input type="email" name="email" id="recover_email" class="form-control" placeholder="Ingresa tu correo" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Recuperar contraseña</button>
                </div>
            </form>
        </div>
    </div>
</div>

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