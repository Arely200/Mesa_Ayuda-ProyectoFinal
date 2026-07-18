<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Mesa de Ayuda</title>
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
        .btn-secondary { background: #E0E0E0; color: #616161; }
        .btn-secondary:hover { background: #BDBDBD; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .card { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .card h3 { margin-bottom: 16px; color: #0D47A1; border-bottom: 2px solid #E3F2FD; padding-bottom: 8px; }
        .barra { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .barra .label { width: 120px; font-size: 14px; color: #212121; }
        .barra .barra-fondo { flex: 1; height: 24px; background: #f0f0f0; border-radius: 12px; overflow: hidden; }
        .barra .barra-llena { height: 100%; border-radius: 12px; transition: width 0.5s; }
        .barra .total { font-size: 14px; font-weight: 600; color: #212121; min-width: 40px; text-align: right; }
        .color-azul { background: #1E88E5; }
        .color-verde { background: #43A047; }
        .color-naranja { background: #FB8C00; }
        .color-rojo { background: #E53935; }
        .color-morado { background: #7B1FA2; }
        .color-cyan { background: #00BCD4; }
        .color-gris { background: #9E9E9E; }
        .stat-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .stat-item { text-align: center; padding: 16px; background: #f8f9fa; border-radius: 8px; }
        .stat-item .numero { font-size: 28px; font-weight: 700; color: #0D47A1; }
        .stat-item .label { font-size: 14px; color: #757575; }
        @media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<header class="header">
    <div class="logo"><i class="bi bi-bullseye"></i> Mesa de Ayuda</div>
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
    <a href="/Mesa_Ayuda/admin/reportes" class="active"><i class="bi bi-bar-chart"></i> Reportes</a>
</nav>

<div class="content">
    <div class="header-actions">
        <h1><i class="bi bi-graph-up-arrow"></i> Estadísticas del Sistema</h1>
        <a href="/Mesa_Ayuda/admin/reportes" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver a Reportes</a>
    </div>

    <div class="stat-row" style="margin-bottom:24px;">
        <div class="stat-item">
            <div class="numero"><?= $estadisticas['total'] ?></div>
            <div class="label">Total Tickets</div>
        </div>
        <div class="stat-item">
            <div class="numero"><?= $estadisticas['tiempo_promedio'] ?>h</div>
            <div class="label">Tiempo Promedio de Resolución</div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Tickets por Estado -->
        <div class="card">
            <h3><i class="bi bi-bar-chart"></i> Tickets por Estado</h3>
            <?php 
            $max = 0;
            foreach ($estadisticas['por_estado'] as $e) {
                if ($e['total'] > $max) $max = $e['total'];
            }
            if ($max === 0) $max = 1;
            $colores = ['En proceso' => 'color-azul', 'En espera' => 'color-naranja', 'Culminada' => 'color-verde'];
            ?>
            <?php foreach ($estadisticas['por_estado'] as $e): ?>
                <div class="barra">
                    <span class="label"><?= $e['estado'] ?></span>
                    <div class="barra-fondo">
                        <div class="barra-llena <?= $colores[$e['estado']] ?? 'color-gris' ?>" style="width: <?= ($e['total'] / $max) * 100 ?>%;"></div>
                    </div>
                    <span class="total"><?= $e['total'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (count($estadisticas['por_estado']) === 0): ?>
                <p style="color:#999;">No hay datos</p>
            <?php endif; ?>
        </div>

        <!-- Tickets por Prioridad -->
        <div class="card">
            <h3><i class="bi bi-lightning-charge"></i> Tickets por Prioridad</h3>
            <?php 
            $max = 0;
            foreach ($estadisticas['por_prioridad'] as $e) {
                if ($e['total'] > $max) $max = $e['total'];
            }
            if ($max === 0) $max = 1;
            $colores = ['Alta' => 'color-rojo', 'Media' => 'color-naranja', 'Baja' => 'color-verde'];
            ?>
            <?php foreach ($estadisticas['por_prioridad'] as $e): ?>
                <div class="barra">
                    <span class="label"><?= $e['prioridad'] ?></span>
                    <div class="barra-fondo">
                        <div class="barra-llena <?= $colores[$e['prioridad']] ?? 'color-gris' ?>" style="width: <?= ($e['total'] / $max) * 100 ?>%;"></div>
                    </div>
                    <span class="total"><?= $e['total'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (count($estadisticas['por_prioridad']) === 0): ?>
                <p style="color:#999;">No hay datos</p>
            <?php endif; ?>
        </div>

        <!-- Tickets por Categoría -->
        <div class="card">
            <h3><i class="bi bi-folder2-open"></i> Tickets por Categoría</h3>
            <?php 
            $max = 0;
            foreach ($estadisticas['por_categoria'] as $e) {
                if ($e['total'] > $max) $max = $e['total'];
            }
            if ($max === 0) $max = 1;
            $coloresCat = ['Soporte Técnico' => 'color-azul', 'Soporte Académico' => 'color-verde', 'Infraestructura' => 'color-naranja', 'Administrativo' => 'color-morado', 'Otros' => 'color-gris'];
            ?>
            <?php foreach ($estadisticas['por_categoria'] as $e): ?>
                <div class="barra">
                    <span class="label"><?= $e['nombre'] ?></span>
                    <div class="barra-fondo">
                        <div class="barra-llena <?= $coloresCat[$e['nombre']] ?? 'color-gris' ?>" style="width: <?= ($e['total'] / $max) * 100 ?>%;"></div>
                    </div>
                    <span class="total"><?= $e['total'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (count($estadisticas['por_categoria']) === 0): ?>
                <p style="color:#999;">No hay datos</p>
            <?php endif; ?>
        </div>

        <!-- Resumen General -->
        <div class="card">
            <h3><i class="bi bi-clipboard-check"></i> Resumen General</h3>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div style="background:#f8f9fa;padding:16px;border-radius:8px;text-align:center;">
                    <div style="font-size:24px;font-weight:700;color:#0D47A1;"><?= $estadisticas['total'] ?></div>
                    <div style="font-size:13px;color:#757575;">Total Tickets</div>
                </div>
                <div style="background:#f8f9fa;padding:16px;border-radius:8px;text-align:center;">
                    <div style="font-size:24px;font-weight:700;color:#43A047;"><?= $estadisticas['tiempo_promedio'] ?>h</div>
                    <div style="font-size:13px;color:#757575;">Tiempo Promedio</div>
                </div>
                <div style="background:#f8f9fa;padding:16px;border-radius:8px;text-align:center;grid-column: span 2;">
                    <div style="font-size:20px;font-weight:700;color:#0D47A1;"><i class="bi bi-trophy"></i> <?= htmlspecialchars($estadisticas['mejor_agente']) ?></div>
                    <div style="font-size:13px;color:#757575;">Mejor Agente (<?= $estadisticas['tickets_mejor_agente'] ?> tickets)</div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>