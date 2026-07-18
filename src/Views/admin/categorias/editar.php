<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría - Mesa de Ayuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: #0D47A1; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; }
        .nav-menu a:hover { color: #0D47A1; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 600px; margin: 0 auto; }
        .card { background: #fff; padding: 32px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #212121; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px 14px; border: 2px solid #E0E0E0; border-radius: 6px; font-size: 14px; font-family: inherit; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #1E88E5; }
        .form-group textarea { min-height: 80px; resize: vertical; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; }
        .btn-primary { background: #1E88E5; color: #fff; }
        .btn-primary:hover { background: #1565C0; }
        .btn-secondary { background: #E0E0E0; color: #616161; }
        .btn-secondary:hover { background: #BDBDBD; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>
<header class="header">
    <div class="logo"><i class="bi bi-bullseye"></i> Mesa de Ayuda</div>
    <div>
        <span><?= $_SESSION['user_nombre'] ?? 'Usuario' ?></span>
        <form method="POST" action="/Mesa_Ayuda/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" style="background:rgba(255,255,255,0.2);color:#fff;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;">Cerrar</button>
        </form>
    </div>
</header>

<nav class="nav-menu">
    <a href="/Mesa_Ayuda/admin/dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/admin/usuarios"><i class="bi bi-people"></i> Usuarios</a>
    <a href="/Mesa_Ayuda/admin/colaboradores"><i class="bi bi-person"></i> Colaboradores</a>
    <a href="/Mesa_Ayuda/admin/tickets"><i class="bi bi-ticket-perforated"></i> Tickets</a>
    <a href="/Mesa_Ayuda/admin/categorias"><i class="bi bi-folder2-open"></i> Categorías</a>
    <a href="/Mesa_Ayuda/admin/reportes"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs"><i class="bi bi-clipboard-check"></i> Logs</a>
</nav>

<div class="content">
    <div class="card">
        <h1>Editar Categoría</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/Mesa_Ayuda/admin/categorias/actualizar/<?= $categoria['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
y
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($categoria['nombre']) ?>" required>
            </div>

            <div lass="form-group">
                <label>Tipo *</label>
                <select name="tipo" required>
                    <option value="Soporte" <?= ($categoria['tipo'] ?? 'Soporte') === 'Soporte' ? 'selected' : '' ?>>Soporte</option>
                    <option value="Académico" <?= ($categoria['tipo'] ?? '') === 'Académico' ? 'selected' : '' ?>>Académico</option>
                </select>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion"><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="activo">
                    <option value="1" <?= $categoria['activo'] ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= !$categoria['activo'] ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="/Mesa_Ayuda/admin/categorias" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
