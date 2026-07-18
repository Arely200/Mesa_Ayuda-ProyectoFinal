<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Editar Colaborador' ?> - Mesa de Ayuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: #0D47A1; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; }
        .nav-menu a:hover { color: #0D47A1; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 800px; margin: 0 auto; }
        .card { background: #fff; padding: 32px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #212121; }
        .form-group input, .form-group select { width: 100%; padding: 10px 14px; border: 2px solid #E0E0E0; border-radius: 6px; font-size: 14px; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #1E88E5; }
        .form-group input.input-error, .form-group select.input-error { border-color: #C62828; background: #FFF5F5; }
        .error-campo { color: #C62828; font-size: 12px; margin-top: 4px; display: block; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; }
        .btn-primary { background: #1E88E5; color: #fff; }
        .btn-primary:hover { background: #1565C0; }
        .btn-secondary { background: #E0E0E0; color: #616161; }
        .btn-secondary:hover { background: #BDBDBD; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
        small { color: #757575; font-size: 12px; }
        .foto-actual { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #E0E0E0; margin-bottom: 8px; display: block; }
        .foto-placeholder { width: 80px; height: 80px; border-radius: 50%; background: #E3F2FD; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: bold; color: #0D47A1; margin-bottom: 8px; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>
<header class="header">
    <div class="logo"><i class="bi bi-bullseye"></i> Mesa de Ayuda</div>
    <div>
        <span><?= $_SESSION['user_nombre'] ?? 'Usuario' ?></span>
    </div>
</header>

<nav class="nav-menu">
    <a href="/Mesa_Ayuda/admin/dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/admin/usuarios"><i class="bi bi-people"></i> Usuarios</a>
    <a href="/Mesa_Ayuda/admin/colaboradores" class="active"><i class="bi bi-person"></i> Colaboradores</a>
    <a href="/Mesa_Ayuda/admin/tickets"><i class="bi bi-ticket-perforated"></i> Tickets</a>
    <a href="/Mesa_Ayuda/admin/reportes"><i class="bi bi-bar-chart"></i> Reportes</a>
</nav>

<div class="content">
    <div class="card">
        <h1><?= $titulo ?? 'Editar Colaborador' ?></h1>

        <?php
        // $campoError viene del controlador: el nombre del campo que
        // falló en la última validación (o null si no hubo error).
        $campoError = $campoError ?? null;
        ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/Mesa_Ayuda/admin/colaboradores/actualizar/<?= $colaborador['id'] ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="primer_nombre">Primer Nombre *</label>
                    <input type="text" id="primer_nombre" name="primer_nombre"
                           class="<?= $campoError === 'primer_nombre' ? 'input-error' : '' ?>"
                           placeholder="Ej: Ana"
                           value="<?= htmlspecialchars($colaborador['primer_nombre']) ?>" required>
                    <?php if ($campoError === 'primer_nombre'): ?><span class="error-campo">Este campo es obligatorio</span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="segundo_nombre">Segundo Nombre</label>
                    <input type="text" id="segundo_nombre" name="segundo_nombre"
                           placeholder="Ej: María"
                           value="<?= htmlspecialchars($colaborador['segundo_nombre'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="primer_apellido">Primer Apellido *</label>
                    <input type="text" id="primer_apellido" name="primer_apellido"
                           class="<?= $campoError === 'primer_apellido' ? 'input-error' : '' ?>"
                           placeholder="Ej: Rodríguez"
                           value="<?= htmlspecialchars($colaborador['primer_apellido']) ?>" required>
                    <?php if ($campoError === 'primer_apellido'): ?><span class="error-campo">Este campo es obligatorio</span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="segundo_apellido">Segundo Apellido</label>
                    <input type="text" id="segundo_apellido" name="segundo_apellido"
                           placeholder="Ej: Gómez"
                           value="<?= htmlspecialchars($colaborador['segundo_apellido'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="identificacion">Identificación *</label>
                    <input type="text" id="identificacion" name="identificacion"
                           class="<?= $campoError === 'identificacion' ? 'input-error' : '' ?>"
                           placeholder="Ej: 123456789"
                           value="<?= htmlspecialchars($colaborador['identificacion']) ?>" required>
                    <?php if ($campoError === 'identificacion'): ?>
                        <span class="error-campo">Identificación inválida o ya registrada. Solo números.</span>
                    <?php else: ?>
                        <small>Solo números</small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo">
                        <option value="M" <?= $colaborador['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= $colaborador['sexo'] === 'F' ? 'selected' : '' ?>>Femenino</option>
                        <option value="Otro" <?= $colaborador['sexo'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                       class="<?= $campoError === 'fecha_nacimiento' ? 'input-error' : '' ?>"
                       value="<?= $colaborador['fecha_nacimiento'] ?>" required>
                <?php if ($campoError === 'fecha_nacimiento'): ?><span class="error-campo">Este campo es obligatorio</span><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico *</label>
                <input type="email" id="email" name="email"
                       class="<?= $campoError === 'email' ? 'input-error' : '' ?>"
                       placeholder="Ej: nombre@correo.com"
                       value="<?= htmlspecialchars($colaborador['email']) ?>" required>
                <?php if ($campoError === 'email'): ?><span class="error-campo">Correo inválido o ya registrado</span><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" minlength="8" maxlength="12"
                       class="<?= $campoError === 'password' ? 'input-error' : '' ?>"
                       placeholder="Dejar vacío para mantener la actual">
                <?php if ($campoError === 'password'): ?>
                    <span class="error-campo">Debe tener entre 8 y 12 caracteres</span>
                <?php else: ?>
                    <small>Solo si deseas cambiarla. Entre 8 y 12 caracteres.</small>
                <?php endif; ?>
            </div>

            <!-- FOTO DE PERFIL -->
            <div class="form-group">
                <label>Foto de Perfil Actual</label>
                <?php if (!empty($colaborador['foto_perfil']) && $colaborador['foto_perfil'] !== 'default-avatar.png'): ?>
                    <img src="/Mesa_Ayuda/public/uploads/avatars/<?= htmlspecialchars($colaborador['foto_perfil']) ?>" alt="Foto actual" class="foto-actual">
                <?php else: ?>
                    <div class="foto-placeholder"><?= strtoupper(substr($colaborador['primer_nombre'], 0, 1)) ?></div>
                <?php endif; ?>
                <label for="foto_perfil">Cambiar Foto</label>
                <input type="file" id="foto_perfil" name="foto_perfil"
                       class="<?= $campoError === 'foto_perfil' ? 'input-error' : '' ?>"
                       accept="image/*">
                <?php if ($campoError === 'foto_perfil'): ?>
                    <span class="error-campo"><?= htmlspecialchars($_SESSION['error'] ?? 'Archivo inválido') ?></span>
                <?php else: ?>
                    <small>Formatos: JPG, PNG, GIF. Máximo 2MB. Dejar vacío para mantener la actual.</small>
                <?php endif; ?>
            </div>

            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="/Mesa_Ayuda/admin/colaboradores" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>