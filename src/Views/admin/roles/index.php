<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Roles y Permisos') ?> - Mesa de Ayuda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>


<header class="header">
    <div class="logo">Mesa de Ayuda</div>
    <div>
        <span><?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario') ?></span>
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
    <a href="/Mesa_Ayuda/admin/reportes"><i class="bi bi-bar-chart"></i> Reportes</a>
    <a href="/Mesa_Ayuda/admin/logs"><i class="bi bi-clipboard-check"></i> Logs</a>
    <a href="/Mesa_Ayuda/admin/roles" class="active"><i class="bi bi-shield-lock"></i> Roles</a>
</nav>


<div class="content">

    <!-- HEADER -->
    <div class="header-actions">
        <h1>Roles y Permisos</h1>
        <a class="btn btn-secondary" href="/Mesa_Ayuda/admin/dashboard">Volver al inicio</a>
    </div>

    <!-- ALERTAS -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- FORMULARIO -->
    <form method="POST" action="/Mesa_Ayuda/admin/roles/actualizar">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <?php foreach ($roles as $rol): ?>
                            <th><?= htmlspecialchars(ucfirst($rol)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modulos as $modulo => $acciones): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars(ucfirst($modulo)) ?></strong></td>
                            <?php foreach ($roles as $rol): ?>
                                <td>
                                    <div class="acciones">
                                        <?php foreach ($acciones as $accion): ?>
                                            <?php $checked = !empty($permisos[$rol][$modulo][$accion]); ?>
                                            <label class="check">
                                                <input type="checkbox" name="permisos[<?= htmlspecialchars($rol) ?>][<?= htmlspecialchars($modulo) ?>][<?= htmlspecialchars($accion) ?>]" value="1" <?= $checked ? 'checked' : '' ?>>
                                                <?= htmlspecialchars(str_replace('_', ' ', $accion)) ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="display:flex; gap:12px; margin-top:20px;">
            <button type="submit" class="btn btn-primary">Guardar permisos</button>
            <a href="/Mesa_Ayuda/admin/dashboard" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
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
        overflow-x: auto;
    }

    .nav-menu a {
        padding: 16px 20px;
        color: #616161;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
        color: #0D47A1;
        border-bottom-color: #1E88E5;
    }

    /* ========== CONTENIDO ========== */
    .content {
        padding: 32px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ========== HEADER DE ACCIONES ========== */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }

    /* ========== PANEL ========== */
    .panel {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    /* ========== TABLA ========== */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px 14px;
        border-bottom: 1px solid #eee;
        text-align: left;
        vertical-align: top;
    }

    th {
        background: #f5f5f5;
        color: #616161;
        font-size: 13px;
    }

    /* ========== ACCIONES ========== */
    .acciones {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 16px;
    }

    label.check {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #424242;
    }

    input[type="checkbox"] {
        width: 16px;
        height: 16px;
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

    .btn-secondary {
        background: #E0E0E0;
        color: #616161;
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

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .content {
            padding: 16px;
        }

        .header {
            flex-direction: column;
            gap: 12px;
        }

        .panel {
            overflow-x: auto;
        }
    }
</style>

</body>
</html>