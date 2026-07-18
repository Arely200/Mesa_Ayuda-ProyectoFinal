<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Editar Usuario' ?> - Mesa de Ayuda</title>
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
        .btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; }
        .btn-primary { background: #1E88E5; color: #fff; }
        .btn-primary:hover { background: #1565C0; }
        .btn-secondary { background: #E0E0E0; color: #616161; }
        .btn-secondary:hover { background: #BDBDBD; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
        small { color: #757575; font-size: 12px; }
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
    <a href="/Mesa_Ayuda/admin/usuarios" class="active"><i class="bi bi-people"></i> Usuarios</a>
    <a href="/Mesa_Ayuda/admin/colaboradores"><i class="bi bi-person"></i> Colaboradores</a>
    <a href="/Mesa_Ayuda/admin/tickets"><i class="bi bi-ticket-perforated"></i> Tickets</a>
   <a href="/Mesa_Ayuda/admin/categorias"><i class="bi bi-folder2-open"></i> Categorías</a>
    <a href="/Mesa_Ayuda/admin/reportes"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs"><i class="bi bi-clipboard-check"></i> Logs</a>
</nav>

<div class="content">
    <div class="card">
        <h1><?= $titulo ?? 'Editar Usuario' ?></h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/Mesa_Ayuda/admin/usuarios/actualizar/<?= $usuario['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña (dejar en blanco para no cambiar)</label>
                <input type="password" id="password" name="password" minlength="8" maxlength="12">
                <small>La contraseña debe tener entre 8 y 12 caracteres</small>
            </div>

            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol">
                    <option value="agente" <?= $usuario['rol'] === 'agente' ? 'selected' : '' ?>>Agente</option>
                    <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    <option value="supervisor" <?= $usuario['rol'] === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                </select>
            </div>

            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="/Mesa_Ayuda/admin/usuarios" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>