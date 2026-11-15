<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Reportes' ?> - Mesa de Ayuda</title>
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

    <!-- HEADER -->
    <div class="header-actions">
        <h1><?= $titulo ?? 'Reportes' ?></h1>
        <div>
            <a href="/Mesa_Ayuda/admin/reportes/estadisticas" class="btn btn-primary">Ver Estadísticas</a>
            <a href="/Mesa_Ayuda/admin/reportes/categorias" class="btn" style="background:#6A1B9A;color:#fff;">Por Categoría</a>
            <a href="/Mesa_Ayuda/admin/reportes/culminados" class="btn" style="background:#43A047;color:#fff;">Tickets Culminados</a>
            <a href="/Mesa_Ayuda/admin/reportes/excel?prioridad=<?= urlencode($prioridad) ?>&estado=<?= urlencode($estado) ?>&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>" class="btn btn-success">Exportar Excel</a>
        </div>
    </div>

    <!-- ESTADÍSTICAS RÁPIDAS -->
    <div class="estadisticas-grid">
        <div class="stat-card">
            <div class="numero"><?= $estadisticas['total'] ?></div>
            <div class="label">Total Tickets</div>
        </div>
        <div class="stat-card">
            <div class="numero"><?= $estadisticas['tiempo_promedio'] ?></div>
            <div class="label">Tiempo Promedio (horas)</div>
            <div class="sub">Tickets culminados</div>
        </div>
        <div class="stat-card">
            <div class="numero"><?= $estadisticas['mejor_agente'] ?></div>
            <div class="label">Mejor Agente</div>
            <div class="sub"><?= $estadisticas['tickets_mejor_agente'] ?> tickets resueltos</div>
        </div>
        <div class="stat-card">
            <div class="numero"><?= count($tickets) ?></div>
            <div class="label">Tickets mostrados</div>
            <div class="sub">Con filtros aplicados</div>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="filtros">
        <form method="GET" action="/Mesa_Ayuda/admin/reportes">
            <div class="grupo">
                <label>Complejidad / Prioridad</label>
                <select name="prioridad">
                    <option value="">Todas</option>
                    <option value="Alta" <?= $prioridad === 'Alta' ? 'selected' : '' ?>>Alta</option>
                    <option value="Media" <?= $prioridad === 'Media' ? 'selected' : '' ?>>Media</option>
                    <option value="Baja" <?= $prioridad === 'Baja' ? 'selected' : '' ?>>Baja</option>
                </select>
            </div>
            <div class="grupo">
                <label>Estado</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="En proceso" <?= $estado === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                    <option value="En espera" <?= $estado === 'En espera' ? 'selected' : '' ?>>En espera</option>
                    <option value="Culminada" <?= $estado === 'Culminada' ? 'selected' : '' ?>>Culminada</option>
                </select>
            </div>
            <div class="grupo">
                <label>Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="<?= $fecha_inicio ?>">
            </div>
            <div class="grupo">
                <label>Fecha Fin</label>
                <input type="date" name="fecha_fin" value="<?= $fecha_fin ?>">
            </div>
            <div class="grupo">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
            <div class="grupo">
                <a href="/Mesa_Ayuda/admin/reportes" class="btn" style="background:#E0E0E0;color:#616161;padding:10px 20px;border-radius:6px;text-decoration:none;">Limpiar</a>
            </div>
        </form>
    </div>

    <!-- TABLA -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Colaborador</th>
                <th>Agente</th>
                <th>Fecha</th>
                <th>Tiempo</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($tickets) === 0): ?>
                <tr><td colspan="9" style="text-align:center;color:#999;padding:32px;">No hay tickets con los filtros seleccionados</td></tr>
            <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td>#<?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['titulo']) ?></td>
                        <td><?= htmlspecialchars($t['categoria_nombre'] ?? 'N/A') ?></td>
                        <td><span class="badge badge-<?= strtolower($t['prioridad']) ?>"><?= $t['prioridad'] ?></span></td>
                        <td><span class="badge badge-<?= strtolower($t['estado']) ?>"><?= $t['estado'] ?></span></td>
                        <td><?= htmlspecialchars(($t['colaborador_nombre'] ?? '') . ' ' . ($t['colaborador_apellido'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($t['agente_nombre'] ?? 'Sin asignar') ?></td>
                        <td><?= date('d/m/Y', strtotime($t['fecha_creacion'])) ?></td>
                        <td>
                            <?php if ($t['estado'] === 'Culminada' && $t['horas_tramite'] !== null): ?>
                                <?= $t['horas_tramite'] ?>h
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
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
                <a href="?pagina=<?= $pagina - 1 ?>&prioridad=<?= $prioridad ?>&estado=<?= $estado ?>&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>" class="prev-next">← Anterior</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i == $pagina): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?pagina=<?= $i ?>&prioridad=<?= $prioridad ?>&estado=<?= $estado ?>&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($pagina < $totalPaginas): ?>
                <a href="?pagina=<?= $pagina + 1 ?>&prioridad=<?= $prioridad ?>&estado=<?= $estado ?>&fecha_inicio=<?= $fecha_inicio ?>&fecha_fin=<?= $fecha_fin ?>" class="prev-next">Siguiente →</a>
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
        flex-wrap: wrap;
        gap: 12px;
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

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    /* ========== FILTROS ========== */
    .filtros {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    .filtros form {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-end;
    }

    .filtros .grupo {
        display: flex;
        flex-direction: column;
    }

    .filtros .grupo label {
        font-size: 13px;
        font-weight: 600;
        color: #616161;
        margin-bottom: 4px;
    }

    .filtros .grupo input,
    .filtros .grupo select {
        padding: 8px 12px;
        border: 2px solid #E0E0E0;
        border-radius: 6px;
        font-size: 14px;
    }

    .filtros .grupo input:focus,
    .filtros .grupo select:focus {
        outline: none;
        border-color: #1E88E5;
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

    /* ========== ESTADÍSTICAS ========== */
    .estadisticas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .stat-card .numero {
        font-size: 32px;
        font-weight: 700;
        color: #0D47A1;
    }

    .stat-card .label {
        font-size: 14px;
        color: #757575;
        margin-top: 4px;
    }

    .stat-card .sub {
        font-size: 12px;
        color: #999;
        margin-top: 4px;
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

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .filtros form {
            flex-direction: column;
            align-items: stretch;
        }

        .filtros .grupo input,
        .filtros .grupo select {
            width: 100%;
        }

        .content {
            padding: 16px;
        }

        .header-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .header {
            flex-direction: column;
            gap: 12px;
            text-align: center;
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

        .estadisticas-grid {
            grid-template-columns: 1fr 1fr;
        }

        .pagination {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }

    @media (max-width: 500px) {
        .estadisticas-grid {
            grid-template-columns: 1fr;
        }

        .pagination-links a,
        .pagination-links span {
            padding: 6px 10px;
            font-size: 12px;
        }
    }
</style>

</body>
</html>