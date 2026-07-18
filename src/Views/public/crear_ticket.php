<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Nuevo Ticket' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: #0D47A1; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; }
        .nav-menu a:hover, .nav-menu a.active { color: #0D47A1; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 800px; margin: 0 auto; }
        .card { background: #fff; padding: 32px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #212121; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 14px; border: 2px solid #E0E0E0; border-radius: 6px; font-size: 14px; font-family: inherit; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #1E88E5; }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
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
    <div class="logo"></i> Portal Colaborador</div>
    <div class="header-usuario">
        <span class="avatar-circulo">
            <?php if (!empty($_SESSION['colaborador_foto']) && $_SESSION['colaborador_foto'] !== 'default-avatar.png'): ?>
                <img src="/Mesa_Ayuda/public/uploads/avatars/<?= htmlspecialchars($_SESSION['colaborador_foto']) ?>" alt="Foto de perfil">
            <?php else: ?>
                <?= htmlspecialchars($_SESSION['colaborador_inicial'] ?? 'U') ?>
            <?php endif; ?>
        </span>
        <span class="nombre"><?= htmlspecialchars($_SESSION['colaborador_nombre'] ?? 'Usuario') ?></span>
        <form method="POST" action="/Mesa_Ayuda/public/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" style="background:rgba(255,255,255,0.2);color:#fff;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;">Cerrar</button>
        </form>
    </div>
</header>

<nav class="nav-menu">
    <a href="/Mesa_Ayuda/public/dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/public/crear-ticket" class="active"><i class="bi bi-plus-circle"></i> Nuevo Ticket</a>
    <a href="/Mesa_Ayuda/public/mis-tickets"><i class="bi bi-clipboard-check"></i> Mis Tickets</a>
    <a href="/Mesa_Ayuda/public/perfil"><i class="bi bi-person-circle"></i> Mi Perfil</a>
</nav>

<div class="content">
    <div class="card">
        <h1><i class="bi bi-plus-circle"></i> Nuevo Ticket</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/Mesa_Ayuda/public/crear-ticket">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="form-group">
                <label>Categoría *</label>
                <select name="categoria_id" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($categorias as $tipo => $items): ?>
                        <?php if (!empty($items)): ?>
                            <optgroup label="<?= htmlspecialchars($tipo) ?>">
                                <?php foreach ($items as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Título *</label>
                <input type="text" name="titulo" required>
            </div>

            <div class="form-group">
                <label>Descripción *</label>
                <textarea name="descripcion" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Prioridad</label>
                    <select name="prioridad">
                        <option value="Baja">Baja</option>
                        <option value="Media" selected>Media</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div style="padding-top:6px;font-size:13px;color:#757575;">Tu ticket será revisado por el equipo de soporte</div>
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="submit" class="btn btn-primary">Crear Ticket</button>
                <a href="/Mesa_Ayuda/public/dashboard" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
