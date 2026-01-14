

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('css/usuario.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Agregar Usuario</h2>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('usuarios.store')); ?>" method="POST" novalidate>
        <?php echo csrf_field(); ?>

        <div class="mb-3">
            <label for="nombre">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                   value="<?php echo e(old('nombre')); ?>" required maxlength="50"
                   placeholder="Ej: Andrea, Juan"
                   title="Máximo 50 caracteres">
            <div id="nombre-error" class="text-danger small mt-1" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label for="apellido">Apellido <span class="text-danger">*</span></label>
            <input type="text" name="apellido" id="apellido" class="form-control"
                   value="<?php echo e(old('apellido')); ?>" required maxlength="50"
                   placeholder="Ej: García, López">
        </div>

        <div class="mb-3">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control"
                   value="<?php echo e(old('email')); ?>" required maxlength="100"
                   placeholder="ejemplo@correo.com"
                   title="Correo electrónico válido">
            <div id="email-error" class="text-danger small mt-1" style="display: none;"></div>
        </div>

        <div class="mb-3">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control"
                   value="<?php echo e(old('telefono')); ?>" maxlength="20"
                   pattern="[0-9+\s\-()]*" title="Solo números y símbolos válidos"
                   placeholder="Ej: +593 987654321">
        </div>

        <div class="mb-3">
            <label for="password">Contraseña <span class="text-danger">*</span></label>
            <input type="password" name="password" id="password" class="form-control"
                   required minlength="8" maxlength="30" autocomplete="new-password"
                   placeholder="Mínimo 8 caracteres"
                   title="Mínimo 8 caracteres">
        </div>

        
        <div class="mb-3">
            <label for="rol">Rol <span class="text-danger">*</span></label>
            <select name="rol" id="rol" class="form-control" required>
                <option value="" disabled selected>-- Selecciona un rol --</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($rol->id); ?>" <?php echo e(old('rol') == $rol->id ? 'selected' : ''); ?>>
                    <?php echo e($rol->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" class="form-check-input" id="activo" value="1"
                   <?php echo e(old('activo') ? 'checked' : ''); ?>>
            <label class="form-check-label" for="activo">Activo</label>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?php echo e(route('usuarios.index')); ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rolInput = document.getElementById('rol');
    const submitBtn = document.querySelector('button[type="submit"]');
    const form = document.querySelector('form');
    let timer = null;

    function validarFormulario() {
        const nombre = nombreInput.value.trim();
        const apellido = apellidoInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const rol = rolInput.value;

        const todosCompletos = nombre && apellido && email && password && rol && password.length >= 8;
        submitBtn.disabled = !todosCompletos;
    }

    function validarCampo(input, tipo) {
        const valor = input.value.trim();
        const errorDiv = document.getElementById(`${tipo}-error`);

        if (!valor) {
            errorDiv.style.display = 'none';
            validarFormulario();
            return;
        }

        fetch(`/usuarios/validar-${tipo}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ [tipo]: valor })
        })
        .then(res => res.json())
        .then(data => {
            if (data.existe) {
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>⚠️ El ${tipo} ya está registrado.`;
                errorDiv.style.display = 'block';
                submitBtn.disabled = true;
            } else {
                errorDiv.style.display = 'none';
                validarFormulario();
            }
        });
    }

    nombreInput.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => validarCampo(nombreInput, 'nombre'), 400);
    });

    emailInput.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => validarCampo(emailInput, 'email'), 400);
    });

    [apellidoInput, passwordInput, rolInput].forEach(input => {
        input.addEventListener('change', validarFormulario);
        input.addEventListener('input', validarFormulario);
    });

    form.addEventListener('submit', function (e) {
        const nombre = nombreInput.value.trim();
        const apellido = apellidoInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const rol = rolInput.value;

        if (!nombre) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Nombre'); nombreInput.focus(); return false; }
        if (!apellido) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Apellido'); apellidoInput.focus(); return false; }
        if (!email) { e.preventDefault(); alert('⚠️ Por favor, completa el campo Email'); emailInput.focus(); return false; }
        if (!password) { e.preventDefault(); alert('⚠️ Por favor, completa la Contraseña'); passwordInput.focus(); return false; }
        if (password.length < 8) { e.preventDefault(); alert('⚠️ La contraseña debe tener al menos 8 caracteres'); passwordInput.focus(); return false; }
        if (!rol) { e.preventDefault(); alert('⚠️ Por favor, selecciona un Rol'); rolInput.focus(); return false; }
    });

    validarFormulario();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Dev-proyectos\aguilas_mobile_react-main\aguilas_saber_php\web\resources\views/usuarios/create.blade.php ENDPATH**/ ?>