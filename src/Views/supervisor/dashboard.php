<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Panel Supervisor') ?> - Mesa de Ayuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; color: #212121; }
        .header { background: #263238; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; overflow-x: auto; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; white-space: nowrap; }
        .nav-menu a:hover, .nav-menu a.active { color: #263238; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 1300px; margin: 0 auto; }
        .welcome { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 24px; display: flex; justify-content: space-between; gap: 16px; align-items: center; }
        .welcome h1 { font-size: 26px; margin-bottom: 6px; }
        .stats { display: grid; grid-template-columns: repeat(5, minmax(150px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .stat-card .numero { font-size: 32px; font-weight: 700; color: #0D47A1; }
        .stat-card .label { font-size: 13px; color: #757575; margin-top: 4px; }
        .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        .panel { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .panel h2 { margin-bottom: 16px; color: #0D47A1; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: #f5f5f5; text-align: left; padding: 10px 12px; color: #616161; font-weight: 600; }
        td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 500; display: inline-block; }
        .badge-proceso { background: #E3F2FD; color: #0D47A1; }
        .badge-espera { background: #FFF8E1; color: #F57F17; }
        .badge-culminada { background: #E8F5E9; color: #2E7D32; }
        .btn { padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #1E88E5; color: #fff; }
        .btn-dark { background: #455A64; color: #fff; }
        .btn-light { background: rgba(255,255,255,0.18); color: #fff; }
        .actions { display: grid; gap: 10px; }
        .agent-row { display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid #eee; padding: 10px 0; }
        .agent-row strong { color: #212121; }
        .agent-row span { color: #0D47A1; font-weight: 700; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
        @media (max-width: 900px) {
            .stats, .grid-2 { grid-template-columns: 1fr; }
            .welcome { align-items: flex-start; flex-direction: column; }
            .content { padding: 16px; }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>
<header class="header">
    <div style="font-size:20px;font-weight:700;"><i class="bi bi-speedometer2"></i> Panel Supervisor</div>
    <div>
        <span><?= htmlspecialchars($nombre) ?></span>
        <form method="POST" action="/Mesa_Ayuda/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" class="btn btn-light" style="margin-left:10px;">Cerrar</button>
        </form>
    </div>
</header>

<nav class="nav-menu">
    <a href="/Mesa_Ayuda/supervisor/dashboard" class="active"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/admin/tickets"><i class="bi bi-ticket-perforated"></i> Tickets</a>
    <a href="/Mesa_Ayuda/admin/reportes"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/reportes/estadisticas"><i class="bi bi-graph-up-arrow"></i> Estadísticas</a>
</nav>

<div class="content">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="welcome">
        <div>
            <h1>Bienvenido, <?= htmlspecialchars($nombre) ?></h1>
            <p style="color:#757575;">Supervisión general de tickets, tiempos de atención y rendimiento del equipo.</p>
        </div>
        <div class="actions" style="grid-template-columns:repeat(3, max-content);">
            <a href="/Mesa_Ayuda/admin/tickets" class="btn btn-primary"><i class="bi bi-ticket-perforated"></i> Ver tickets</a>
            <a href="/Mesa_Ayuda/admin/reportes" class="btn btn-dark"><i class="bi bi-bar-chart"></i> Reportes</a>
            <a href="/Mesa_Ayuda/admin/reportes/excel" class="btn btn-dark"><i class="bi bi-download"></i> Excel</a>
        </div>
    </div>

    <div class="stats">
        <div class="stat-card"><div class="numero"><?= $totales['total'] ?></div><div class="label">Total tickets</div></div>
        <div class="stat-card"><div class="numero"><?= $totales['proceso'] ?></div><div class="label">En proceso</div></div>
        <div class="stat-card"><div class="numero"><?= $totales['espera'] ?></div><div class="label">En espera</div></div>
        <div class="stat-card"><div class="numero"><?= $totales['culminados'] ?></div><div class="label">Culminados</div></div>
        <div class="stat-card"><div class="numero"><?= $totales['promedio'] ?>h</div><div class="label">Promedio resolución</div></div>
    </div>

    <div class="grid-2">
        <div class="panel">
            <h2><i class="bi bi-clock-history"></i> Tickets recientes</h2>
            <?php if (empty($tickets_recientes)): ?>
                <p style="color:#999;">No hay tickets registrados.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Colaborador</th>
                            <th>Agente</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets_recientes as $t): ?>
                            <?php $estadoClase = strtolower(str_replace([' ', 'á'], ['-', 'a'], $t['estado'])); ?>
                            <tr>
                                <td>#<?= $t['id'] ?></td>
                                <td><?= htmlspecialchars($t['titulo']) ?></td>
                                <td><?= htmlspecialchars($t['colaborador_nombre'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($t['agente_nombre'] ?? 'Sin asignar') ?></td>
                                <td><span class="badge badge-<?= htmlspecialchars($estadoClase) ?>"><?= htmlspecialchars($t['estado']) ?></span></td>
                                <td><a href="/Mesa_Ayuda/admin/tickets/ver/<?= $t['id'] ?>" class="btn btn-primary" style="padding:6px 12px;font-size:12px;">Ver</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="panel">
            <h2><i class="bi bi-person-check"></i> Agentes</h2>
            <?php if (empty($rendimiento_agentes)): ?>
                <p style="color:#999;">No hay agentes activos.</p>
            <?php else: ?>
                <?php foreach ($rendimiento_agentes as $agente): ?>
                    <div class="agent-row">
                        <strong><?= htmlspecialchars($agente['nombre']) ?></strong>
                        <span><?= (int)$agente['total'] ?> resueltos</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
