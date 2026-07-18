<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Tickets Culminados' ?> - Mesa de Ayuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: #0D47A1; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; }
        .nav-menu a:hover, .nav-menu a.active { color: #0D47A1; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 1400px; margin: 0 auto; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #1E88E5; color: #fff; }
        .btn-primary:hover { background: #1565C0; }
        .btn-sm { padding: 6px 14px; font-size: 12px; }
        table { width: 100%; background: #fff; border-collapse: collapse; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        th { background: #f5f5f5; text-align: left; padding: 12px 16px; color: #616161; font-weight: 600; }
        td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; }
        tr:hover td { background: #FAFAFA; }
        .badge-tiempo { background: #E8F5E9; color: #2E7D32; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 12px; }
        .pagination-info { color: #757575; font-size: 14px; }
        .pagination-links { display: flex; gap: 8px; flex-wrap: wrap; }
        .pagination-links a, .pagination-links span { padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; }
        .pagination-links a { background: #f5f5f5; color: #616161; border: 1px solid #E0E0E0; }
        .pagination-links a:hover { background: #E0E0E0; }
        .pagination-links .active { background: #1E88E5; color: #fff; border: 1px solid #1E88E5; }
        .pagination-links .prev-next { background: #E0E0E0; color: #616161; border: none; }
        .pagination-links .prev-next:hover { background: #BDBDBD; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<header class="header">
    <div class="logo">Mesa de Ayuda</div>
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
    <a href="/Mesa_Ayuda/admin/reportes" class="active"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs"><i class="bi bi-clipboard-check"></i> Logs</a>
</nav>

<div class="content">

    <div class="header-actions">
        <h1><i class="bi bi-check2-circle"></i> Tickets Culminados</h1>
        <div>
            <a href="/Mesa_Ayuda/admin/reportes" class="btn" style="background:#E0E0E0;color:#616161;"><i class="bi bi-arrow-left"></i> Volver a Reportes</a>
        </div>
    </div>

    <p style="color:#757575;margin-bottom:20px;">
        Tickets finalizados junto con el tiempo total que tomó atenderlos, desde su creación hasta su culminación.
        Haz clic en cualquier fila para ver el detalle completo y la solución dada por el agente.
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Colaborador</th>
                <th>Agente</th>
                <th>Fecha Creación</th>
                <th>Fecha Culminación</th>
                <th>Tiempo Tramitado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr><td colspan="9" style="text-align:center;color:#999;padding:32px;">Aún no hay tickets culminados</td></tr>
            <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                    <tr style="cursor:pointer;" onclick="window.location='/Mesa_Ayuda/admin/tickets/ver/<?= $t['id'] ?>'">
                        <td>#<?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['titulo']) ?></td>
                        <td><?= htmlspecialchars($t['categoria_nombre'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars(trim(($t['colaborador_nombre'] ?? '') . ' ' . ($t['colaborador_apellido'] ?? ''))) ?></td>
                        <td><?= htmlspecialchars($t['agente_nombre'] ?? 'Sin asignar') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($t['fecha_creacion'])) ?></td>
                        <td><?= $t['fecha_culminacion'] ? date('d/m/Y H:i', strtotime($t['fecha_culminacion'])) : 'N/A' ?></td>
                        <td><span class="badge-tiempo"><?= $t['tiempo_tramite'] ?></span></td>
                        <td><a href="/Mesa_Ayuda/admin/tickets/ver/<?= $t['id'] ?>" class="btn btn-primary btn-sm" onclick="event.stopPropagation();"><i class="bi bi-eye"></i> Ver</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ======================================== -->
    <!-- PAGINACIÓN -->
    <!-- ======================================== -->
    <div class="pagination">
        <div class="pagination-info">
            Mostrando <?= count($tickets) ?> de <?= $total ?> tickets culminados
        </div>
        <div class="pagination-links">
            <?php if ($pagina > 1): ?>
                <a href="?pagina=<?= $pagina - 1 ?>" class="prev-next">◀ Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i == $pagina): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?pagina=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
                <a href="?pagina=<?= $pagina + 1 ?>" class="prev-next">Siguiente ▶</a>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
