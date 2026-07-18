<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Estadísticas por Categoría' ?> - Mesa de Ayuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: #0D47A1; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; }
        .nav-menu a:hover, .nav-menu a.active { color: #0D47A1; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 1200px; margin: 0 auto; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #1E88E5; color: #fff; }
        .btn-primary:hover { background: #1565C0; }
        .btn-success { background: #43A047; color: #fff; }
        .btn-success:hover { background: #2E7D32; }
        .filtros { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 24px; }
        .filtros form { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; }
        .filtros .grupo { display: flex; flex-direction: column; }
        .filtros .grupo label { font-size: 13px; font-weight: 600; color: #616161; margin-bottom: 4px; }
        .filtros .grupo input, .filtros .grupo select { padding: 8px 12px; border: 2px solid #E0E0E0; border-radius: 6px; font-size: 14px; }
        .resumen { background: #fff; padding: 20px 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .resumen .numero { font-size: 32px; font-weight: 700; color: #0D47A1; }
        .resumen .label { font-size: 14px; color: #757575; }
        table { width: 100%; background: #fff; border-collapse: collapse; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        th { background: #f5f5f5; text-align: left; padding: 12px 16px; color: #616161; font-weight: 600; }
        td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; }
        .badge-tipo { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .badge-soporte { background: #E3F2FD; color: #0D47A1; }
        .badge-academico { background: #F3E5F5; color: #6A1B9A; }
        .barra-wrap { background: #f0f0f0; border-radius: 6px; height: 10px; width: 140px; overflow: hidden; }
        .barra { background: #1E88E5; height: 100%; }
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
    <script>
        function togglePeriodo() {
            var periodo = document.getElementById('periodo').value;
            document.getElementById('grupo-mes').style.display = (periodo === 'mensual') ? 'flex' : 'none';
            document.getElementById('grupo-semestre').style.display = (periodo === 'semestral') ? 'flex' : 'none';
        }
    </script>
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
        <h1><i class="bi bi-pie-chart"></i> Estadísticas por Categoría</h1>
        <a href="/Mesa_Ayuda/admin/reportes" class="btn" style="background:#E0E0E0;color:#616161;"><i class="bi bi-arrow-left"></i> Volver a Reportes</a>
    </div>

    <!-- ======================================== -->
    <!-- FILTRO DE PERIODO -->
    <!-- ======================================== -->
    <div class="filtros">
        <form method="GET" action="/Mesa_Ayuda/admin/reportes/categorias">
            <div class="grupo">
                <label>Periodo</label>
                <select name="periodo" id="periodo" onchange="togglePeriodo()">
                    <option value="mensual"   <?= $periodo === 'mensual'   ? 'selected' : '' ?>>Mensual</option>
                    <option value="semestral" <?= $periodo === 'semestral' ? 'selected' : '' ?>>Semestral</option>
                    <option value="anual"     <?= $periodo === 'anual'     ? 'selected' : '' ?>>Anual</option>
                </select>
            </div>
            <div class="grupo">
                <label>Año</label>
                <input type="number" name="anio" value="<?= $anio ?>" min="2020" max="2100" style="width:100px;">
            </div>
            <div class="grupo" id="grupo-mes" style="<?= $periodo === 'mensual' ? '' : 'display:none;' ?>">
                <label>Mes</label>
                <select name="mes">
                    <?php
                        $nombresMes = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
                        foreach ($nombresMes as $num => $nombre):
                    ?>
                        <option value="<?= $num ?>" <?= $mes == $num ? 'selected' : '' ?>><?= $nombre ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grupo" id="grupo-semestre" style="<?= $periodo === 'semestral' ? '' : 'display:none;' ?>">
                <label>Semestre</label>
                <select name="semestre">
                    <option value="1" <?= $semestre == 1 ? 'selected' : '' ?>>I Semestre (Ene - Jun)</option>
                    <option value="2" <?= $semestre == 2 ? 'selected' : '' ?>>II Semestre (Jul - Dic)</option>
                </select>
            </div>
            <div class="grupo">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Consultar</button>
            </div>
            <div class="grupo">
                <a href="/Mesa_Ayuda/admin/reportes/categorias/excel?periodo=<?= urlencode($periodo) ?>&anio=<?= $anio ?>&mes=<?= $mes ?>&semestre=<?= $semestre ?>" class="btn btn-success"><i class="bi bi-download"></i> Exportar Excel</a>
            </div>
        </form>
    </div>

    <div class="resumen">
        <div>
            <div class="numero"><?= $totalAtendidosPeriodo ?></div>
            <div class="label">Tickets atendidos en el periodo (<?= date('d/m/Y', strtotime($fechaInicio)) ?> — <?= date('d/m/Y', strtotime($fechaFin)) ?>)</div>
        </div>
    </div>

    <!-- ======================================== -->
    <!-- TABLA POR CATEGORÍA -->
    <!-- ======================================== -->
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Tipo</th>
                <th>Total Atendidos</th>
                <th>Proporción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categorias)): ?>
                <tr><td colspan="4" style="text-align:center;color:#999;padding:32px;">No hay categorías registradas</td></tr>
            <?php else: ?>
                <?php $max = max(1, max(array_column($categorias, 'total_atendidos'))); ?>
                <?php foreach ($categorias as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nombre']) ?></td>
                        <td><span class="badge-tipo badge-<?= strtolower($c['tipo']) === 'académico' || strtolower($c['tipo']) === 'academico' ? 'academico' : 'soporte' ?>"><?= htmlspecialchars($c['tipo']) ?></span></td>
                        <td><strong><?= $c['total_atendidos'] ?></strong></td>
                        <td>
                            <div class="barra-wrap">
                                <div class="barra" style="width: <?= ($c['total_atendidos'] / $max) * 100 ?>%;"></div>
                            </div>
                        </td>
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
            Mostrando <?= count($categorias) ?> de <?= $total ?> categorías
        </div>
        <div class="pagination-links">
            <?php $qs = "periodo=$periodo&anio=$anio&mes=$mes&semestre=$semestre"; ?>
            <?php if ($pagina > 1): ?>
                <a href="?<?= $qs ?>&pagina=<?= $pagina - 1 ?>" class="prev-next">◀ Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i == $pagina): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?<?= $qs ?>&pagina=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
                <a href="?<?= $qs ?>&pagina=<?= $pagina + 1 ?>" class="prev-next">Siguiente ▶</a>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
