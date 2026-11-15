<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets - Mesa de Ayuda</title>
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
    <a href="/Mesa_Ayuda/admin/tickets" class="active"><i class="bi bi-ticket-perforated"></i> Tickets</a>
    <a href="/Mesa_Ayuda/admin/categorias"><i class="bi bi-folder2-open"></i> Categorías</a>
    <a href="/Mesa_Ayuda/admin/reportes"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs"><i class="bi bi-clipboard-check"></i> Logs</a>
</nav>

<div class="content">
    <div class="header-actions">
        <h1>Gestión de Tickets</h1>
        <a href="/Mesa_Ayuda/admin/tickets/crear" class="btn btn-primary">+ Nuevo Ticket</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Colaborador</th>
                <th>Categoría</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Agente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $t): ?>
            <tr>
                <td>#<?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['titulo']) ?></td>
                <td><?= htmlspecialchars($t['colaborador_nombre'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($t['categoria_nombre'] ?? 'N/A') ?></td>
                <td><span class="badge badge-<?= strtolower($t['prioridad']) ?>"><?= $t['prioridad'] ?></span></td>
                <td><span class="badge badge-<?= strtolower($t['estado']) ?>"><?= $t['estado'] ?></span></td>
                <td><?= htmlspecialchars($t['agente_nombre'] ?? 'Sin asignar') ?></td>
                <td>
                    <a href="/Mesa_Ayuda/admin/tickets/ver/<?= $t['id'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                    
                    <?php if ($t['estado'] === 'En proceso'): ?>
                        <form method="POST" action="/Mesa_Ayuda/admin/tickets/en-espera/<?= $t['id'] ?>" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
                            <button type="submit" class="btn btn-warning btn-sm" title="Poner en espera"><i class="bi bi-pause-circle"></i></button>
                        </form>
                    <?php endif; ?>

                    <?php if ($t['estado'] !== 'Culminada'): ?>
                        <form method="POST" action="/Mesa_Ayuda/admin/tickets/culminar/<?= $t['id'] ?>" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
                            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i></button>
                        </form>
                    <?php endif; ?>
                    
                    <form method="POST" action="/Mesa_Ayuda/admin/tickets/eliminar/<?= $t['id'] ?>" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <div class="pagination-info">
            Mostrando <?= count($tickets) ?> de <?= $total ?> tickets
        </div>
        <div class="pagination-links">
            <?php if ($pagina > 1): ?>
                <a href="?pagina=<?= $pagina - 1 ?>" class="prev-next">← Anterior</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i == $pagina): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?pagina=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($pagina < $totalPaginas): ?>
                <a href="?pagina=<?= $pagina + 1 ?>" class="prev-next">Siguiente →</a>
            <?php endif; ?>
        </div>
        <div class="pagination-select">
            <label style="font-size:14px;color:#757575;">Mostrar:</label>
            <select onchange="window.location.href='?porPagina='+this.value">
                <option value="5" <?= $porPagina == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $porPagina == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $porPagina == 25 ? 'selected' : '' ?>>25</option>
                <option value="50" <?= $porPagina == 50 ? 'selected' : '' ?>>50</option>
            </select>
        </div>
    </div>
</div>

<style>
    /* ========== RESET Y BASE ========== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f5f5f5;
    }

    /* ========== HEADER ========== */
    .header {
        background: #0D47A1;
        color: #fff;
        padding: 16px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ========== NAVEGACIÓN ========== */
    .nav-menu {
        background: #fff;
        padding: 0 32px;
        display: flex;
        gap: 8px;
        border-bottom: 1px solid #E0E0E0;
    }

    .nav-menu a {
        padding: 16px 20px;
        color: #616161;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-bottom: 3px solid transparent;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
        color: #0D47A1;
        border-bottom-color: #1E88E5;
    }

    /* ========== CONTENIDO ========== */
    .content {
        padding: 32px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ========== HEADER ACCIONES ========== */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    /* ========== BOTONES ========== */
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: #1E88E5;
        color: #fff;
    }

    .btn-primary:hover {
        background: #1565C0;
    }

    .btn-success {
        background: #43A047;
        color: #fff;
    }

    .btn-success:hover {
        background: #2E7D32;
    }

    .btn-danger {
        background: #E53935;
        color: #fff;
    }

    .btn-danger:hover {
        background: #C62828;
    }

    .btn-warning {
        background: #FB8C00;
        color: #fff;
    }

    .btn-warning:hover {
        background: #E65100;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    /* ========== TABLA ========== */
    table {
        width: 100%;
        background: #fff;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    th {
        background: #f5f5f5;
        text-align: left;
        padding: 12px 16px;
        color: #616161;
        font-weight: 600;
    }

    td {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
    }

    /* ========== BADGES ========== */
    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-proceso {
        background: #E3F2FD;
        color: #0D47A1;
    }

    .badge-espera {
        background: #FFF8E1;
        color: #F57F17;
    }

    .badge-culminada {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .badge-alta {
        background: #FFEBEE;
        color: #C62828;
    }

    .badge-media {
        background: #FFF3E0;
        color: #E65100;
    }

    .badge-baja {
        background: #E8F5E9;
        color: #2E7D32;
    }

    /* ========== PAGINACIÓN ========== */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        color: #757575;
        font-size: 14px;
    }

    .pagination-links {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .pagination-links a,
    .pagination-links span {
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
    }

    .pagination-links a {
        background: #f5f5f5;
        color: #616161;
        border: 1px solid #E0E0E0;
    }

    .pagination-links a:hover {
        background: #E0E0E0;
    }

    .pagination-links .active {
        background: #1E88E5;
        color: #fff;
        border: 1px solid #1E88E5;
    }

    .pagination-links .prev-next {
        background: #E0E0E0;
        color: #616161;
        border: none;
    }

    .pagination-links .prev-next:hover {
        background: #BDBDBD;
    }

    .pagination-select {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-select select {
        padding: 6px 12px;
        border: 2px solid #E0E0E0;
        border-radius: 6px;
        font-size: 14px;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            gap: 12px;
            text-align: center;
        }

        .content {
            padding: 16px;
        }

        .header-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .nav-menu {
            overflow-x: auto;
            padding: 0 16px;
        }

        .nav-menu a {
            padding: 12px 14px;
            font-size: 13px;
            white-space: nowrap;
        }

        .pagination {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .pagination-select {
            margin-top: 8px;
        }
    }

    @media (max-width: 500px) {
        .pagination-links a,
        .pagination-links span {
            padding: 6px 10px;
            font-size: 12px;
        }
    }
</style>

</body>
</html>