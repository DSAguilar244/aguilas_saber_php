@extends('layouts.guest')

@section('styles')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-box">
        <div class="logo-box text-center mb-3">
            <img src="{{ asset('static/img/fondo_aguilas_saber.png') }}" alt="Logo Las Águilas del Saber" class="img-fluid mb-2" style="max-width: 100px;">
            <h3 class="fw-bold text-danger mb-1">Las Águilas del Saber</h3>
            <p class="text-muted small mb-0">Sistema de Gestión Escolar</p>
        </div>
        <h2 class="title">Iniciar Sesión</h2>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                <span id="toggle-password" class="toggle-icon"><i class="fas fa-eye-slash"></i></span>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" id="remember_me" class="form-check-input">
                <label for="remember_me" class="form-check-label">Recordarme</label>
            </div>
            <button type="submit" class="btn btn-danger w-100">Iniciar sesión</button>
        </form>

        <div class="text-center mt-3">
            <a href="#" class="link-recover" data-bs-toggle="modal" data-bs-target="#recoverPasswordModal">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>

<!-- Modal Recuperar Contraseña -->
<div class="modal fade" id="recoverPasswordModal" tabindex="-1" aria-labelledby="recoverPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('password.email') }}" id="recoverPasswordForm">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="recoverPasswordModalLabel">
                        <i class="fas fa-key me-2"></i>Recuperar Contraseña
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                    <label for="recover_email" class="form-label fw-semibold">Correo electrónico</label>
                    <input type="email" name="email" id="recover_email" class="form-control mb-3" placeholder="ejemplo@correo.com" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-paper-plane me-1"></i>Enviar enlace
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Éxito -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="fas fa-check-circle me-2"></i>¡Correo Enviado!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-envelope-open-text fa-4x text-success mb-3"></i>
                <h5 class="mb-3">Revisa tu correo electrónico</h5>
                <p class="text-muted">Te hemos enviado un enlace para restablecer tu contraseña. Por favor revisa tu bandeja de entrada.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="fas fa-check me-1"></i>Entendido
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggle-password').addEventListener('click', function () {
        const input = document.getElementById('password');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        this.innerHTML = isHidden ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // Mostrar modal de éxito si hay status de password reset
    @if(session('status'))
        document.addEventListener('DOMContentLoaded', function() {
            // Cerrar modal de recuperar contraseña si está abierto
            const recoverModal = document.getElementById('recoverPasswordModal');
            if (recoverModal) {
                const bsRecoverModal = bootstrap.Modal.getInstance(recoverModal);
                if (bsRecoverModal) {
                    bsRecoverModal.hide();
                }
            }
            
            // Mostrar modal de éxito
            const successEl = document.getElementById('successModal');
            const successModal = new bootstrap.Modal(successEl);
            successModal.show();
            // Auto-cerrar después de 3 segundos
            setTimeout(() => {
                successModal.hide();
            }, 3000);
        });
    @endif
</script>
@endsection