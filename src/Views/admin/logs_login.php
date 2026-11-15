<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Logs de Login' ?> - Mesa de Ayuda</title>
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
    <a href="/Mesa_Ayuda/admin/reportes"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs" class="active"><i class="bi bi-clipboard-check"></i> Logs</a>
</nav>


<div class="content">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1>Logs de Login</h1>
            <div class="subtitle">Registro de todos los intentos de inicio de sesión en el sistema</div>
        </div>
        <div class="actions">
            <a href="/Mesa_Ayuda/admin/dashboard" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <!-- ESTADÍSTICAS RÁPIDAS -->
    <?php
    $total = count($logs);
    $exitosos = 0;
    $fallidos = 0;
    $bloqueados = 0;

    foreach ($logs as $log) {
        if ($log['exitoso']) {
            $exitosos++;
        } else {
            $fallidos++;
        }
        if (strpos(strtolower($log['mensaje'] ?? ''), 'bloqueada') !== false) {
            $bloqueados++;
        }
    }
    ?>

    <div class="stats-resumen">
        <div class="stat">
            <div class="numero"><?= $total ?></div>
            <div class="label">Total Intentos</div>
        </div>
        <div class="stat">
            <div class="numero exitoso"><?= $exitosos ?></div>
            <div class="label">Exitosos</div>
        </div>
        <div class="stat">
            <div class="numero fallido"><?= $fallidos ?></div>
            <div class="label">Fallidos</div>
        </div>
        <div class="stat">
            <div class="numero bloqueado"><?= $bloqueados ?></div>
            <div class="label">Bloqueos</div>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="filters">
        <div class="group">
            <label>Estado</label>
            <select id="filter_estado">
                <option value="">Todos</option>
                <option value="1">Exitoso</option>
                <option value="0">Fallido</option>
            </select>
        </div>
        <div class="group">
            <label>Email</label>
            <input type="text" id="filter_email" placeholder="Buscar por email...">
        </div>
        <div class="group">
            <label>Fecha desde</label>
            <input type="date" id="filter_fecha_desde">
        </div>
        <div class="group">
            <label>Fecha hasta</label>
            <input type="date" id="filter_fecha_hasta">
        </div>
        <div class="actions">
            <button class="btn btn-primary" onclick="aplicarFiltros()">Filtrar</button>
            <button class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar</button>
        </div>
    </div>

    <!-- TABLA -->
    <div class="table-wrapper">
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
            <tbody id="tabla_logs">
                <?php if (count($logs) === 0): ?>
                    <tr class="empty-row">
                        <td colspan="6">No hay registros de login</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr data-exitoso="<?= $log['exitoso'] ?>" data-email="<?= strtolower($log['email']) ?>" data-fecha="<?= date('Y-m-d', strtotime($log['fecha'])) ?>">
                            <td><?= $log['id'] ?></td>
                            <td><?= htmlspecialchars($log['email']) ?></td>
                            <td><?= htmlspecialchars($log['ip']) ?></td>
                            <td><?= date('d/m/Y H:i:s', strtotime($log['fecha'])) ?></td>
                            <td>
                                <span class="badge <?= $log['exitoso'] ? 'badge-exitoso' : 'badge-fallido' ?>">
                                    <?= $log['exitoso'] ? 'Exitoso' : 'Fallido' ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($log['mensaje'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>




<script>
    function aplicarFiltros() {
        const estado = document.getElementById('filter_estado').value;
        const email = document.getElementById('filter_email').value.toLowerCase();
        const fechaDesde = document.getElementById('filter_fecha_desde').value;
        const fechaHasta = document.getElementById('filter_fecha_hasta').value;

        const filas = document.querySelectorAll('#tabla_logs tr');

        filas.forEach(fila => {
            if (fila.classList.contains('empty-row')) {
                return;
            }

            let mostrar = true;

            if (estado !== '') {
                const exitoso = fila.dataset.exitoso;
                if (exitoso !== estado) {
                    mostrar = false;
                }
            }

            if (email !== '') {
                const emailFila = fila.dataset.email || '';
                if (!emailFila.includes(email)) {
                    mostrar = false;
                }
            }

            if (fechaDesde !== '') {
                const fechaFila = fila.dataset.fecha || '';
                if (fechaFila < fechaDesde) {
                    mostrar = false;
                }
            }

            if (fechaHasta !== '') {
                const fechaFila = fila.dataset.fecha || '';
                if (fechaFila > fechaHasta) {
                    mostrar = false;
                }
            }

            fila.style.display = mostrar ? '' : 'none';
        });
    }

    function limpiarFiltros() {
        document.getElementById('filter_estado').value = '';
        document.getElementById('filter_email').value = '';
        document.getElementById('filter_fecha_desde').value = '';
        document.getElementById('filter_fecha_hasta').value = '';

        const filas = document.querySelectorAll('#tabla_logs tr');
        filas.forEach(fila => {
            fila.style.display = '';
        });
    }

    let timeoutEmail;
    document.getElementById('filter_email').addEventListener('input', function() {
        clearTimeout(timeoutEmail);
        timeoutEmail = setTimeout(aplicarFiltros, 300);
    });

    document.getElementById('filter_estado').addEventListener('change', aplicarFiltros);
    document.getElementById('filter_fecha_desde').addEventListener('change', aplicarFiltros);
    document.getElementById('filter_fecha_hasta').addEventListener('change', aplicarFiltros);
</script>


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
        max-width: 1200px;
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

    .btn-success {
        background: #2E7D32;
        color: #ffffff;
    }

    .btn-success:hover {
        background: #1B5E20;
    }

    /* ========== FILTROS ========== */
    .filters {
        background: #ffffff;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        display: flex;
        flex-wrap: wrap;
        gap: 12px 16px;
        align-items: flex-end;
        margin-bottom: 20px;
        border: 1px solid #edf2f7;
    }

    .filters .group {
        display: flex;
        flex-direction: column;
    }

    .filters .group label {
        font-size: 12px;
        font-weight: 600;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
    }

    .filters .group input,
    .filters .group select {
        padding: 7px 12px;
        border: 1.5px solid #E0E0E0;
        border-radius: 6px;
        font-size: 13px;
        background: #fafafa;
        transition: border 0.2s;
        font-family: inherit;
        color: #212121;
    }

    .filters .group input:focus,
    .filters .group select:focus {
        outline: none;
        border-color: #1E88E5;
        background: #ffffff;
    }

    .filters .actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    /* ========== ESTADÍSTICAS RÁPIDAS ========== */
    .stats-resumen {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stats-resumen .stat {
        background: #ffffff;
        padding: 14px 18px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        text-align: center;
        border: 1px solid #edf2f7;
    }

    .stats-resumen .stat .numero {
        font-size: 26px;
        font-weight: 700;
        color: #0D47A1;
    }

    .stats-resumen .stat .label {
        font-size: 12px;
        color: #757575;
        margin-top: 2px;
    }

    .stats-resumen .stat .numero.exitoso {
        color: #2E7D32;
    }

    .stats-resumen .stat .numero.fallido {
        color: #C62828;
    }

    .stats-resumen .stat .numero.bloqueado {
        color: #E65100;
    }

    /* ========== TABLA ========== */
    .table-wrapper {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        border: 1px solid #edf2f7;
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
        padding: 12px 16px;
        font-weight: 600;
        color: #616161;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    td {
        padding: 11px 16px;
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
    .badge {
        display: inline-block;
        padding: 3px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-exitoso {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .badge-fallido {
        background: #FFEBEE;
        color: #C62828;
    }

    .badge-bloqueado {
        background: #FFF3E0;
        color: #E65100;
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

        .filters {
            flex-direction: column;
            align-items: stretch;
        }

        .filters .group input,
        .filters .group select {
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
            min-width: 650px;
        }

        th,
        td {
            padding: 10px 12px;
        }
    }

    @media (max-width: 500px) {
        .stats-resumen {
            grid-template-columns: 1fr;
        }
    }
</style>

</body>
</html>