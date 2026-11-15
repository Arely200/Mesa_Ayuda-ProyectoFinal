<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Mesa de Ayuda</title>
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
    <a href="/Mesa_Ayuda/admin/categorias"><i class="bi bi-folder2-open"></i> Categorías</a>
    <a href="/Mesa_Ayuda/admin/reportes" class="active"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs"><i class="bi bi-clipboard-check"></i> Logs</a>
</nav>


<div class="content">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1>Estadisticas del Sistema</h1>
            <div class="subtitle">Resumen de tickets por estado, prioridad y categoria</div>
        </div>
        <div class="actions">
            <a href="/Mesa_Ayuda/admin/reportes" class="btn btn-secondary">← Volver a Reportes</a>
            <a href="/Mesa_Ayuda/admin/reportes/estadisticas/excel?periodo=<?= $periodo ?? 'mensual' ?>&anio=<?= $anio ?? date('Y') ?>&mes=<?= $mes ?? date('n') ?>" class="btn btn-success">Exportar a Excel</a>
        </div>
    </div>

    <!-- FILTROS DE PERÍODO -->
    <div class="filtros-periodo">
        <span class="label">Periodo:</span>

        <a href="?periodo=mensual&mes=<?= $mes ?? date('n') ?>&anio=<?= $anio ?? date('Y') ?>" class="btn-periodo <?= ($periodo ?? 'mensual') === 'mensual' ? 'active' : '' ?>">Mensual</a>
        <a href="?periodo=semestral&semestre=<?= $semestre ?? (date('n') <= 6 ? 1 : 2) ?>&anio=<?= $anio ?? date('Y') ?>" class="btn-periodo <?= ($periodo ?? '') === 'semestral' ? 'active' : '' ?>">Semestral</a>
        <a href="?periodo=anual&anio=<?= $anio ?? date('Y') ?>" class="btn-periodo <?= ($periodo ?? '') === 'anual' ? 'active' : '' ?>">Anual</a>

        <?php if (($periodo ?? 'mensual') === 'mensual'): ?>
            <select name="mes" id="select_mes">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= ($mes ?? date('n')) == $m ? 'selected' : '' ?>>
                        <?php
                        $nombres_meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        echo $nombres_meses[$m - 1];
                        ?>
                    </option>
                <?php endfor; ?>
            </select>
        <?php endif; ?>

        <select name="anio" id="select_anio">
            <?php for ($a = 2023; $a <= date('Y'); $a++): ?>
                <option value="<?= $a ?>" <?= ($anio ?? date('Y')) == $a ? 'selected' : '' ?>><?= $a ?></option>
            <?php endfor; ?>
        </select>

        <button class="btn-filtrar" onclick="aplicarFiltros()">Filtrar</button>
    </div>

    <!-- INDICADOR DE PERÍODO SELECCIONADO -->
    <div class="periodo-info">
        Mostrando estadisticas para:
        <?php
        $periodo = $periodo ?? 'mensual';
        $anio = $anio ?? date('Y');
        $mes = $mes ?? date('n');
        $semestre = $semestre ?? (date('n') <= 6 ? 1 : 2);
        $nombres_meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        if ($periodo === 'mensual') {
            echo $nombres_meses[$mes - 1] . ' de ' . $anio;
        } elseif ($periodo === 'semestral') {
            echo ($semestre == 1 ? 'Primer' : 'Segundo') . ' semestre de ' . $anio;
        } else {
            echo 'Año ' . $anio;
        }
        ?>
    </div>

    <!-- ESTADÍSTICAS RÁPIDAS -->
    <div class="quick-stats">
        <div class="stat-item">
            <div class="number"><?= $estadisticas['total'] ?></div>
            <div class="label">Total Tickets</div>
        </div>
        <div class="stat-item">
            <div class="number"><?= $estadisticas['tiempo_promedio'] ?>h</div>
            <div class="label">Tiempo Promedio de Resolucion</div>
        </div>
    </div>

    <!-- GRID DE TARJETAS -->
    <div class="stats-grid">

        <!-- Tickets por Estado -->
        <div class="card">
            <h3>Tickets por Estado</h3>
            <?php
            $max = 0;
            foreach ($estadisticas['por_estado'] as $e) {
                if ($e['total'] > $max) $max = $e['total'];
            }
            if ($max === 0) $max = 1;
            $colores = [
                'En proceso' => 'fill-azul',
                'En espera'  => 'fill-naranja',
                'Culminada'  => 'fill-verde-azulado'
            ];
            ?>
            <?php foreach ($estadisticas['por_estado'] as $e): ?>
                <div class="bar">
                    <span class="label"><?= $e['estado'] ?></span>
                    <div class="track">
                        <div class="fill <?= $colores[$e['estado']] ?? 'fill-gris' ?>" style="width: <?= ($e['total'] / $max) * 100 ?>%;"></div>
                    </div>
                    <span class="value"><?= $e['total'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (count($estadisticas['por_estado']) === 0): ?>
                <p style="color:#999;">No hay datos disponibles</p>
            <?php endif; ?>
        </div>

        <!-- Tickets por Prioridad -->
        <div class="card">
            <h3>Tickets por Prioridad</h3>
            <?php
            $max = 0;
            foreach ($estadisticas['por_prioridad'] as $e) {
                if ($e['total'] > $max) $max = $e['total'];
            }
            if ($max === 0) $max = 1;
            $colores = [
                'Alta'  => 'fill-rojo',
                'Media' => 'fill-naranja',
                'Baja'  => 'fill-verde-azulado'
            ];
            ?>
            <?php foreach ($estadisticas['por_prioridad'] as $e): ?>
                <div class="bar">
                    <span class="label"><?= $e['prioridad'] ?></span>
                    <div class="track">
                        <div class="fill <?= $colores[$e['prioridad']] ?? 'fill-gris' ?>" style="width: <?= ($e['total'] / $max) * 100 ?>%;"></div>
                    </div>
                    <span class="value"><?= $e['total'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (count($estadisticas['por_prioridad']) === 0): ?>
                <p style="color:#999;">No hay datos disponibles</p>
            <?php endif; ?>
        </div>

        <!-- Tickets por Categoría -->
        <div class="card">
            <h3>Tickets por Categoria</h3>
            <?php
            $max = 0;
            foreach ($estadisticas['por_categoria'] as $e) {
                if ($e['total'] > $max) $max = $e['total'];
            }
            if ($max === 0) $max = 1;
            $coloresCat = [
                'Soporte Técnico'    => 'fill-azul-oscuro',
                'Soporte Académico'  => 'fill-azul',
                'Infraestructura'    => 'fill-azul-claro',
                'Administrativo'     => 'fill-azul-mas-claro',
                'Otros'              => 'fill-gris'
            ];
            ?>
            <?php foreach ($estadisticas['por_categoria'] as $e): ?>
                <div class="bar">
                    <span class="label"><?= $e['nombre'] ?></span>
                    <div class="track">
                        <div class="fill <?= $coloresCat[$e['nombre']] ?? 'fill-gris' ?>" style="width: <?= ($e['total'] / $max) * 100 ?>%;"></div>
                    </div>
                    <span class="value"><?= $e['total'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (count($estadisticas['por_categoria']) === 0): ?>
                <p style="color:#999;">No hay datos disponibles</p>
            <?php endif; ?>
        </div>

        <!-- Resumen General -->
        <div class="card">
            <h3>Resumen General</h3>
            <div class="summary-grid">
                <div class="item">
                    <div class="number"><?= $estadisticas['total'] ?></div>
                    <div class="label">Total Tickets</div>
                </div>
                <div class="item">
                    <div class="number"><?= $estadisticas['tiempo_promedio'] ?>h</div>
                    <div class="label">Tiempo Promedio</div>
                </div>
                <div class="item full">
                    <div class="number"><?= htmlspecialchars($estadisticas['mejor_agente']) ?></div>
                    <div class="label">Mejor Agente — <?= $estadisticas['tickets_mejor_agente'] ?> tickets resueltos</div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    function aplicarFiltros() {
        const periodo = document.querySelector('.btn-periodo.active');
        const mes = document.getElementById('select_mes');
        const anio = document.getElementById('select_anio');

        let url = '?';

        if (periodo) {
            const periodoText = periodo.textContent.trim().toLowerCase();
            url += 'periodo=' + periodoText;
        } else {
            url += 'periodo=mensual';
        }

        if (mes) {
            url += '&mes=' + mes.value;
        }

        if (anio) {
            url += '&anio=' + anio.value;
        }

        window.location.href = url;
    }
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
        align-items: flex-start;
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

    .btn-success {
        background: #2E7D32;
        color: #ffffff;
    }

    .btn-success:hover {
        background: #1B5E20;
    }

    /* ========== FILTROS ========== */
    .filtros-periodo {
        background: #ffffff;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .filtros-periodo .label {
        font-weight: 600;
        color: #0D47A1;
        font-size: 14px;
    }

    .filtros-periodo .btn-periodo {
        padding: 6px 16px;
        border: 1px solid #E0E0E0;
        border-radius: 6px;
        background: #f5f5f5;
        color: #616161;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .filtros-periodo .btn-periodo:hover {
        background: #E3F2FD;
        border-color: #1E88E5;
    }

    .filtros-periodo .btn-periodo.active {
        background: #0D47A1;
        color: #ffffff;
        border-color: #0D47A1;
    }

    .filtros-periodo select {
        padding: 6px 12px;
        border: 1px solid #E0E0E0;
        border-radius: 6px;
        font-size: 13px;
        background: #ffffff;
        color: #212121;
    }

    .filtros-periodo select:focus {
        outline: none;
        border-color: #1E88E5;
    }

    .filtros-periodo .btn-filtrar {
        padding: 6px 20px;
        background: #0D47A1;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
    }

    .filtros-periodo .btn-filtrar:hover {
        background: #1565C0;
    }

    /* ========== INDICADOR DE PERÍODO ========== */
    .periodo-info {
        background: #E3F2FD;
        padding: 10px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #0D47A1;
        font-weight: 500;
    }

    /* ========== ESTADÍSTICAS RÁPIDAS ========== */
    .quick-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .quick-stats .stat-item {
        background: #ffffff;
        padding: 20px 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .quick-stats .stat-item .number {
        font-size: 32px;
        font-weight: 700;
        color: #0D47A1;
    }

    .quick-stats .stat-item .label {
        font-size: 14px;
        color: #757575;
        margin-top: 4px;
    }

    /* ========== GRID DE TARJETAS ========== */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    /* ========== TARJETAS ========== */
    .card {
        background: #ffffff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .card h3 {
        font-size: 16px;
        font-weight: 600;
        color: #0D47A1;
        border-bottom: 2px solid #E3F2FD;
        padding-bottom: 10px;
        margin-bottom: 16px;
    }

    /* ========== BARRAS ========== */
    .bar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .bar .label {
        width: 130px;
        font-size: 14px;
        color: #212121;
        flex-shrink: 0;
    }

    .bar .track {
        flex: 1;
        height: 22px;
        background: #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .bar .track .fill {
        height: 100%;
        border-radius: 12px;
        transition: width 0.5s ease;
    }

    .bar .value {
        font-size: 14px;
        font-weight: 600;
        color: #212121;
        min-width: 40px;
        text-align: right;
    }

    /* Colores */
    .fill-azul-oscuro {
        background: #0D47A1;
    }
    .fill-azul {
        background: #1E88E5;
    }
    .fill-azul-claro {
        background: #42A5F5;
    }
    .fill-azul-mas-claro {
        background: #64B5F6;
    }
    .fill-verde-azulado {
        background: #00897B;
    }
    .fill-naranja {
        background: #E65100;
    }
    .fill-rojo {
        background: #C62828;
    }
    .fill-gris {
        background: #9E9E9E;
    }

    /* ========== RESUMEN GENERAL ========== */
    .summary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .summary-grid .item {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        text-align: center;
    }

    .summary-grid .item .number {
        font-size: 24px;
        font-weight: 700;
        color: #0D47A1;
    }

    .summary-grid .item .label {
        font-size: 13px;
        color: #757575;
        margin-top: 2px;
    }

    .summary-grid .item.full {
        grid-column: span 2;
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

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .quick-stats {
            grid-template-columns: 1fr 1fr;
        }

        .summary-grid {
            grid-template-columns: 1fr 1fr;
        }

        .bar .label {
            width: 100px;
            font-size: 13px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header .actions {
            width: 100%;
        }

        .filtros-periodo {
            padding: 12px 16px;
        }
    }

    @media (max-width: 500px) {
        .quick-stats {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .summary-grid .item.full {
            grid-column: span 1;
        }

        .bar {
            flex-wrap: wrap;
            gap: 6px;
        }

        .bar .label {
            width: 100%;
            font-size: 13px;
        }

        .bar .value {
            min-width: 30px;
            font-size: 13px;
        }

        .filtros-periodo {
            flex-direction: column;
            align-items: stretch;
        }

        .filtros-periodo select {
            width: 100%;
        }
    }
</style>

</body>
</html>