<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de Login - Mesa de Ayuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: #0D47A1; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; }
        .nav-menu a:hover, .nav-menu a.active { color: #0D47A1; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 1200px; margin: 0 auto; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; background: #fff; border-collapse: collapse; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        th { background: #f5f5f5; text-align: left; padding: 12px 16px; color: #616161; font-weight: 600; }
        td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; }
        .badge-exitoso { background: #E8F5E9; color: #2E7D32; padding: 4px 12px; border-radius: 12px; font-size: 12px; }
        .badge-fallido { background: #FFEBEE; color: #C62828; padding: 4px 12px; border-radius: 12px; font-size: 12px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; }
        .btn-secondary { background: #E0E0E0; color: #616161; }
        .btn-secondary:hover { background: #BDBDBD; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<header class="header">
    <div class="logo"></i> Mesa de Ayuda</div>
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
    <div class="header-actions">
        <h1><i class="bi bi-clipboard-check"></i> Logs de Login</h1>
        <a href="/Mesa_Ayuda/admin/dashboard" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>IP</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Mensaje</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($logs) === 0): ?>
                <tr><td colspan="6" style="text-align:center;color:#999;padding:32px;">No hay registros de login</td></tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= $log['id'] ?></td>
                        <td><?= htmlspecialchars($log['email']) ?></td>
                        <td><?= htmlspecialchars($log['ip']) ?></td>
                        <td><?= date('d/m/Y H:i:s', strtotime($log['fecha'])) ?></td>
                        <td>
                            <span class="badge-<?= $log['exitoso'] ? 'exitoso' : 'fallido' ?>">
                                <?= $log['exitoso'] ? '<i class="bi bi-check-circle"></i> Éxito' : '<i class="bi bi-x-circle"></i> Fallido' ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($log['mensaje'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>