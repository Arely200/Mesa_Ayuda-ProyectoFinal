<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Mis Tickets' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>


<header class="header">
    <div class="logo">Portal Colaborador</div>
    <div class="header-usuario">
        <span class="avatar-circulo">
            <?php if (!empty($_SESSION['colaborador_foto']) && $_SESSION['colaborador_foto'] !== 'default-avatar.png'): ?>
                <img src="/Mesa_Ayuda/public/uploads/avatars/<?= htmlspecialchars($_SESSION['colaborador_foto']) ?>" alt="Foto de perfil">
            <?php else: ?>
                <?= htmlspecialchars($_SESSION['colaborador_inicial'] ?? 'U') ?>
            <?php endif; ?>
        </span>
        <span class="nombre"><?= htmlspecialchars($_SESSION['colaborador_nombre'] ?? 'Usuario') ?></span>
        <form method="POST" action="/Mesa_Ayuda/public/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" style="background:rgba(255,255,255,0.2);color:#fff;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;">Cerrar</button>
        </form>
    </div>
</header>


<nav class="nav-menu">
    <a href="/Mesa_Ayuda/public/dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/public/crear-ticket"><i class="bi bi-plus-circle"></i> Nuevo Ticket</a>
    <a href="/Mesa_Ayuda/public/mis-tickets" class="active"><i class="bi bi-clipboard-check"></i> Mis Tickets</a>
    <a href="/Mesa_Ayuda/public/perfil"><i class="bi bi-person-circle"></i> Mi Perfil</a>
</nav>

<div class="content">

    <!-- HEADER -->
    <div class="header-actions">
        <h1>Mis Tickets</h1>
        <a href="/Mesa_Ayuda/public/crear-ticket" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Ticket</a>
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
                <th>Fecha</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($tickets) === 0): ?>
                <tr><td colspan="7" style="text-align:center;color:#999;padding:32px;">No tienes tickets creados</td></tr>
            <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td>#<?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['titulo']) ?></td>
                        <td><?= htmlspecialchars($t['categoria_nombre'] ?? 'N/A') ?></td>
                        <td><span class="badge badge-<?= strtolower($t['prioridad']) ?>"><?= $t['prioridad'] ?></span></td>
                        <td><span class="badge badge-<?= strtolower($t['estado']) ?>"><?= $t['estado'] ?></span></td>
                        <td><?= date('d/m/Y', strtotime($t['fecha_creacion'])) ?></td>
                        <td><a href="/Mesa_Ayuda/public/ver-ticket/<?= $t['id'] ?>" class="btn btn-primary btn-sm">Ver</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<style>

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

    .header-usuario {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 14px;
    }

    .avatar-circulo {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        color: #fff;
        overflow: hidden;
    }

    .avatar-circulo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
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

        .header-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
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
    }
</style>

</body>
</html>