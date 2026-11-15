<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Tickets Culminados' ?> - Mesa de Ayuda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>


<header class="header">
    <div class="logo">Mesa de Ayuda</div>
    <div class="user-info">
        <span><?= $_SESSION['user_nombre'] ?? 'Usuario' ?></span>
        <form method="POST" action="/Mesa_Ayuda/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" class="btn-logout">Cerrar</button>
        </form>
    </div>
</header>


<nav class="nav-menu">
    <a href="/Mesa_Ayuda/admin/dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/admin/usuarios"><i class="bi bi-people"></i> Usuarios</a>
    <a href="/Mesa_Ayuda/admin/colaboradores"><i class="bi bi-person"></i> Colaboradores</a>
    <a href="/Mesa_Ayuda/admin/tickets"><i class="bi bi-ticket-perforated"></i> Tickets</a>
    <a href="/Mesa_Ayuda/admin/categorias"><i class="bi bi-folder2-open"></i> Categorias</a>
    <a href="/Mesa_Ayuda/admin/reportes" class="active"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs"><i class="bi bi-clipboard-check"></i> Logs</a>
</nav>


<div class="content">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1>Tickets Culminados</h1>
            <div class="subtitle">Tickets finalizados con su tiempo de atencion y solucion registrada</div>
        </div>
        <div class="actions">
            <a href="/Mesa_Ayuda/admin/reportes" class="btn btn-secondary">Volver a Reportes</a>
        </div>
    </div>

    <!-- ESTADÍSTICAS DE TIEMPOS -->
    <?php
    $total_tickets = count($tickets);

    $tiempos_minutos = array_filter(array_column($tickets, 'minutos_tramite'), function($t) {
        return $t !== null && is_numeric($t);
    });

    $promedio = !empty($tiempos_minutos) ? round(array_sum($tiempos_minutos) / count($tiempos_minutos) / 60, 1) : 0;
    $minimo = !empty($tiempos_minutos) ? round(min($tiempos_minutos) / 60, 1) : 0;
    $maximo = !empty($tiempos_minutos) ? round(max($tiempos_minutos) / 60, 1) : 0;
    ?>

    <div class="stats-resumen">
        <div class="stat">
            <div class="numero"><?= $total_tickets ?></div>
            <div class="label">Total Culminados</div>
            <div class="detalle">Tickets finalizados</div>
        </div>
        <div class="stat">
            <div class="numero"><?= $promedio ?>h</div>
            <div class="label">Tiempo Promedio</div>
            <div class="detalle">Por ticket atendido</div>
        </div>
        <div class="stat">
            <div class="numero"><?= $minimo ?>h</div>
            <div class="label">Tiempo Minimo</div>
            <div class="detalle">Ticket mas rapido</div>
        </div>
        <div class="stat">
            <div class="numero"><?= $maximo ?>h</div>
            <div class="label">Tiempo Maximo</div>
            <div class="detalle">Ticket mas lento</div>
        </div>
    </div>

    <!-- TABLA -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Categoria</th>
                    <th>Colaborador</th>
                    <th>Agente</th>
                    <th>Fecha Creacion</th>
                    <th>Fecha Culminacion</th>
                    <th>Tiempo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tickets)): ?>
                    <tr class="empty-row">
                        <td colspan="9">No hay tickets culminados registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tickets as $t): ?>
                        <?php
                        $tiempo_texto = $t['tiempo_tramite'] ?? 'N/A';
                        $clase_tiempo = '';
                        if (strpos($tiempo_texto, 'd') !== false) {
                            $clase_tiempo = 'muy-largo';
                        } elseif (strpos($tiempo_texto, 'h') !== false && strpos($tiempo_texto, 'd') === false) {
                            preg_match('/(\d+)h/', $tiempo_texto, $matches);
                            if (!empty($matches) && $matches[1] > 12) {
                                $clase_tiempo = 'largo';
                            }
                        }
                        ?>
                        <tr style="cursor:pointer;" onclick="window.location='/Mesa_Ayuda/admin/tickets/ver/<?= $t['id'] ?>'">
                            <td><strong>#<?= $t['id'] ?></strong></td>
                            <td><?= htmlspecialchars($t['titulo']) ?></td>
                            <td><?= htmlspecialchars($t['categoria_nombre'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars(trim(($t['colaborador_nombre'] ?? '') . ' ' . ($t['colaborador_apellido'] ?? ''))) ?></td>
                            <td><?= htmlspecialchars($t['agente_nombre'] ?? 'Sin asignar') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($t['fecha_creacion'])) ?></td>
                            <td><?= $t['fecha_culminacion'] ? date('d/m/Y H:i', strtotime($t['fecha_culminacion'])) : 'N/A' ?></td>
                            <td><span class="badge-tiempo <?= $clase_tiempo ?>"><?= $tiempo_texto ?></span></td>
                            <td>
                                <a href="/Mesa_Ayuda/admin/tickets/ver/<?= $t['id'] ?>" class="badge-ver" onclick="event.stopPropagation();">Ver detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <div class="pagination">
        <div class="info">
            Mostrando <?= count($tickets) ?> de <?= $total ?> tickets culminados
        </div>
        <div class="links">
            <?php if ($pagina > 1): ?>
                <a href="?pagina=<?= $pagina - 1 ?>" class="prev-next">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i == $pagina): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?pagina=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
                <a href="?pagina=<?= $pagina + 1 ?>" class="prev-next">Siguiente</a>
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
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f5f5;
        color: #212121;
    }

    /* ========== HEADER ========== */
    .header {
        background: #0D47A1;
        color: #ffffff;
        padding: 16px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header .logo {
        font-size: 20px;
        font-weight: 600;
    }

    .header .user-info {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 14px;
    }

    .header .user-info .btn-logout {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
    }

    .header .user-info .btn-logout:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* ========== NAVEGACIÓN ========== */
    .nav-menu {
        background: #ffffff;
        padding: 0 40px;
        display: flex;
        gap: 4px;
        border-bottom: 1px solid #E0E0E0;
    }

    .nav-menu a {
        padding: 14px 18px;
        color: #616161;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }

    .nav-menu a:hover {
        color: #0D47A1;
        border-bottom-color: #1E88E5;
    }

    .nav-menu a.active {
        color: #0D47A1;
        border-bottom-color: #1E88E5;
    }

    /* ========== CONTENIDO ========== */
    .content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 32px 24px;
    }

    /* ========== HEADER DE ACCIONES ========== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 8px;
    }

    .page-header h1 {
        font-size: 24px;
        font-weight: 600;
        color: #0D47A1;
    }

    .page-header .subtitle {
        font-size: 14px;
        color: #757575;
        margin-top: 2px;
    }

    .page-header .actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* ========== BOTONES ========== */
    .btn {
        padding: 8px 18px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
    }

    .btn-secondary {
        background: #E0E0E0;
        color: #616161;
    }

    .btn-secondary:hover {
        background: #BDBDBD;
    }

    .btn-primary {
        background: #0D47A1;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #1565C0;
    }

    /* ========== ESTADÍSTICAS ========== */
    .stats-resumen {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stats-resumen .stat {
        background: #ffffff;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .stats-resumen .stat .numero {
        font-size: 28px;
        font-weight: 700;
        color: #0D47A1;
    }

    .stats-resumen .stat .label {
        font-size: 13px;
        color: #757575;
        margin-top: 2px;
    }

    .stats-resumen .stat .detalle {
        font-size: 12px;
        color: #9E9E9E;
        margin-top: 2px;
    }

    /* ========== TABLA ========== */
    .table-wrapper {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead {
        background: #f8f9fa;
        border-bottom: 2px solid #E0E0E0;
    }

    th {
        text-align: left;
        padding: 14px 16px;
        font-weight: 600;
        color: #616161;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    td {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        color: #212121;
    }

    tr:hover td {
        background: #fafcff;
    }

    tr:last-child td {
        border-bottom: none;
    }

    /* ========== BADGES ========== */
    .badge-tiempo {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #E8F5E9;
        color: #2E7D32;
    }

    .badge-tiempo.largo {
        background: #FFF3E0;
        color: #E65100;
    }

    .badge-tiempo.muy-largo {
        background: #FFEBEE;
        color: #C62828;
    }

    .badge-ver {
        padding: 4px 14px;
        border-radius: 6px;
        background: #E3F2FD;
        color: #0D47A1;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        transition: background 0.2s;
    }

    .badge-ver:hover {
        background: #BBDEFB;
    }

    /* ========== PAGINACIÓN ========== */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }

    .pagination .info {
        font-size: 14px;
        color: #757575;
    }

    .pagination .links {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .pagination .links a,
    .pagination .links span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid #E0E0E0;
        color: #616161;
        background: #ffffff;
    }

    .pagination .links a:hover {
        background: #f5f5f5;
        border-color: #BDBDBD;
    }

    .pagination .links .active {
        background: #0D47A1;
        color: #ffffff;
        border-color: #0D47A1;
    }

    .pagination .links .prev-next {
        background: transparent;
        border: none;
        color: #616161;
    }

    .pagination .links .prev-next:hover {
        background: #f5f5f5;
        border-radius: 6px;
    }

    /* ========== FILA VACÍA ========== */
    .empty-row td {
        text-align: center;
        color: #9E9E9E;
        padding: 48px 16px;
        font-size: 15px;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 820px) {
        .header {
            padding: 12px 20px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .nav-menu {
            padding: 0 16px;
            overflow-x: auto;
            gap: 2px;
        }

        .nav-menu a {
            padding: 12px 14px;
            font-size: 13px;
            white-space: nowrap;
        }

        .content {
            padding: 16px 12px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header .actions {
            width: 100%;
        }

        .stats-resumen {
            grid-template-columns: 1fr 1fr;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            font-size: 13px;
            min-width: 700px;
        }

        th,
        td {
            padding: 10px 12px;
        }

        .pagination {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
    }

    @media (max-width: 500px) {
        .stats-resumen {
            grid-template-columns: 1fr;
        }

        .pagination .links {
            justify-content: center;
        }
    }
</style>

</body>
</html>