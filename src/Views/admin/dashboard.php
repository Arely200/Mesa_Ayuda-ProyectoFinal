<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mesa de Ayuda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<!-- ============================================================
HEADER
============================================================ -->
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

<!-- ============================================================
NAVEGACIÓN
============================================================ -->
<nav class="nav-menu">
    <a href="/Mesa_Ayuda/admin/dashboard" class="active"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/admin/usuarios"><i class="bi bi-people"></i> Usuarios</a>
    <a href="/Mesa_Ayuda/admin/colaboradores"><i class="bi bi-person"></i> Colaboradores</a>
    <a href="/Mesa_Ayuda/admin/tickets"><i class="bi bi-ticket-perforated"></i> Tickets</a>
    <a href="/Mesa_Ayuda/admin/categorias"><i class="bi bi-folder2-open"></i> Categorías</a>
    <a href="/Mesa_Ayuda/admin/reportes"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs"><i class="bi bi-clipboard-check"></i> Logs</a>
    <a href="/Mesa_Ayuda/admin/roles"><i class="bi bi-shield-lock"></i> Roles</a>
</nav>


<div class="content">

    <!-- BIENVENIDA -->
    <div class="welcome">
        <h1>¡Bienvenido, <?= $_SESSION['user_nombre'] ?? 'Usuario' ?>!</h1>
        <p>Aquí tienes un resumen del sistema</p>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="cards">
        <div class="card blue">
            <div class="numero"><?= $total_tickets ?? 0 ?></div>
            <div class="label">Total Tickets</div>
        </div>
        <div class="card orange">
            <div class="numero"><?= $en_proceso ?? 0 ?></div>
            <div class="label">En Proceso</div>
        </div>
        <div class="card green">
            <div class="numero"><?= $culminados ?? 0 ?></div>
            <div class="label">Culminados</div>
        </div>
        <div class="card purple">
            <div class="numero"><?= $agentes ?? 0 ?></div>
            <div class="label">Agentes Activos</div>
        </div>
        <div class="card logs" onclick="window.location.href='/Mesa_Ayuda/admin/logs'">
            <div class="numero"><?= $total_logs ?? 0 ?></div>
            <div class="label">Intentos de Login</div>
            <div class="sub"><?= $fallidos_recientes ?? 0 ?> fallidos (1h) →</div>
        </div>
    </div>

    <!-- PANELES -->
    <div class="grid-2">
        <!-- Tickets Recientes -->
        <div class="panel">
            <h3>Tickets Recientes</h3>
            <?php if (count($tickets_recientes ?? []) === 0): ?>
                <p style="color:#999;">No hay tickets recientes</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Colaborador</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets_recientes as $t): ?>
                            <tr>
                                <td>#<?= $t['id'] ?></td>
                                <td><?= htmlspecialchars($t['titulo']) ?></td>
                                <td><?= htmlspecialchars($t['colaborador'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge badge-<?= strtolower($t['estado']) ?>">
                                        <?= $t['estado'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Acciones Rápidas -->
        <div class="panel">
            <h3>Acciones Rápidas</h3>
            <div class="actions">
                <a href="/Mesa_Ayuda/admin/tickets" class="btn">Ver todos los tickets</a>
                <a href="/Mesa_Ayuda/admin/usuarios" class="btn btn-green">Gestionar usuarios</a>
                <a href="/Mesa_Ayuda/admin/roles" class="btn" style="background:#455A64;">Gestionar roles</a>
                <a href="/Mesa_Ayuda/admin/logs" class="btn" style="background:#7B1FA2;">Ver logs de login</a>
            </div>
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

    /* ========== BIENVENIDA ========== */
    .welcome {
        margin-bottom: 32px;
    }

    .welcome h1 {
        font-size: 28px;
        color: #212121;
    }

    /* ========== TARJETAS ========== */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .card {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .card .numero {
        font-size: 32px;
        font-weight: 700;
        color: #212121;
    }

    .card .label {
        font-size: 14px;
        color: #757575;
    }

    .card.blue .numero {
        color: #1E88E5;
    }

    .card.orange .numero {
        color: #FB8C00;
    }

    .card.green .numero {
        color: #43A047;
    }

    .card.purple .numero {
        color: #7B1FA2;
    }

    .card.logs {
        background: #F3E5F5;
        cursor: pointer;
        border: 2px solid #7B1FA2;
        transition: transform 0.2s;
    }

    .card.logs:hover {
        transform: scale(1.02);
    }

    .card.logs .numero {
        color: #7B1FA2;
    }

    .card.logs .sub {
        font-size: 12px;
        color: #7B1FA2;
        margin-top: 4px;
    }

    /* ========== GRID ========== */
    .grid-2 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    /* ========== PANELES ========== */
    .panel {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .panel h3 {
        margin-bottom: 16px;
    }

    /* ========== TABLA ========== */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    th {
        text-align: left;
        padding: 8px 0;
        border-bottom: 1px solid #E0E0E0;
    }

    td {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    /* ========== BADGES ========== */
    .badge {
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
    }

    .badge-culminada {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .badge-proceso {
        background: #E3F2FD;
        color: #0D47A1;
    }

    .badge-espera {
        background: #FFF8E1;
        color: #F57F17;
    }

    /* ========== BOTONES ========== */
    .btn {
        display: inline-block;
        padding: 10px 20px;
        background: #1E88E5;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
    }

    .btn:hover {
        background: #1565C0;
    }

    .btn-green {
        background: #43A047;
    }

    .btn-green:hover {
        background: #2E7D32;
    }

    .actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .actions .btn {
        text-align: center;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
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

        .content {
            padding: 16px;
        }
    }
</style>

</body>
</html>