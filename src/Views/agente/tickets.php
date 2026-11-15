<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tickets - Mesa de Ayuda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>


<header class="header">
    <div style="font-size:20px;font-weight:700;">Panel del Agente</div>
    <div>
        <span><?= $_SESSION['user_nombre'] ?? '' ?></span>
        <form method="POST" action="/Mesa_Ayuda/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" class="btn-logout">Cerrar Sesión</button>
        </form>
    </div>
</header>


<nav class="nav-menu">
    <a href="/Mesa_Ayuda/agente/dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/agente/tickets" class="active"><i class="bi bi-ticket-perforated"></i> Mis Tickets</a>
</nav>


<div class="content">

    <!-- ALERTAS -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- HEADER -->
    <div class="header-actions">
        <h1>Mis Tickets Asignados</h1>
    </div>

    <!-- TABLA -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Colaborador</th>
                <th>Categoría</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr><td colspan="8" style="text-align:center;color:#999;padding:32px;">No tienes tickets asignados</td></tr>
            <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td>#<?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['titulo']) ?></td>
                        <td><?= htmlspecialchars($t['colaborador_nombre'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($t['categoria_nombre'] ?? 'N/A') ?></td>
                        <td><span class="badge badge-<?= $t['prioridad'] ?>"><?= $t['prioridad'] ?></span></td>
                        <td><span class="badge badge-<?= $t['estado'] ?>"><?= $t['estado'] ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($t['fecha_creacion'])) ?></td>
                        <td><a href="/Mesa_Ayuda/agente/ticket/<?= $t['id'] ?>" class="btn btn-primary"><i class="bi bi-eye"></i> Ver</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINACIÓN -->
    <div class="pagination">
        <div class="pagination-info">
            Mostrando <?= count($tickets) ?> de <?= $total ?> tickets
        </div>
        <div class="pagination-links">
            <?php if ($pagina > 1): ?>
                <a href="?pagina=<?= $pagina - 1 ?>">← Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i == $pagina): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?pagina=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
                <a href="?pagina=<?= $pagina + 1 ?>">Siguiente →</a>
            <?php endif; ?>
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
        background: #1565C0;
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
        color: #1565C0;
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
        display: inline-block;
    }

    .badge-Alta {
        background: #FFEBEE;
        color: #C62828;
    }

    .badge-Media {
        background: #FFF3E0;
        color: #E65100;
    }

    .badge-Baja {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .badge-En.proceso {
        background: #E3F2FD;
        color: #0D47A1;
    }

    .badge-En {
        background: #FFF8E1;
        color: #F57F17;
    }

    .badge-Culminada {
        background: #E8F5E9;
        color: #2E7D32;
    }

    /* ========== BOTONES ========== */
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
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

    .btn-logout {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 12px;
    }

    .btn-logout:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* ========== ALERTAS ========== */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        border: 1px solid #C8E6C9;
    }

    .alert-error {
        background: #FFEBEE;
        color: #C62828;
        border: 1px solid #FFCDD2;
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

        .nav-menu {
            overflow-x: auto;
            padding: 0 16px;
        }

        .nav-menu a {
            padding: 12px 14px;
            font-size: 13px;
            white-space: nowrap;
        }

        .header-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .pagination {
            flex-direction: column;
            align-items: center;
            text-align: center;
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