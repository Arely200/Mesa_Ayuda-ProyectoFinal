<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Dashboard' ?> - Colaborador</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: #0D47A1; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; }
        .nav-menu a:hover, .nav-menu a.active { color: #0D47A1; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 1400px; margin: 0 auto; }
        .welcome { margin-bottom: 32px; }
        .welcome h1 { font-size: 28px; color: #212121; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .card { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); text-align: center; }
        .card .numero { font-size: 32px; font-weight: 700; color: #0D47A1; }
        .card .label { font-size: 14px; color: #757575; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #1E88E5; color: #fff; }
        .btn-primary:hover { background: #1565C0; }
        .btn-success { background: #43A047; color: #fff; }
        .btn-success:hover { background: #2E7D32; }
        table { width: 100%; background: #fff; border-collapse: collapse; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        th { background: #f5f5f5; text-align: left; padding: 12px 16px; color: #616161; font-weight: 600; }
        td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; }
        .badge { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .badge-proceso { background: #E3F2FD; color: #0D47A1; }
        .badge-espera { background: #FFF8E1; color: #F57F17; }
        .badge-culminada { background: #E8F5E9; color: #2E7D32; }
        .badge-alta { background: #FFEBEE; color: #C62828; }
        .badge-media { background: #FFF3E0; color: #E65100; }
        .badge-baja { background: #E8F5E9; color: #2E7D32; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
        .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        .panel { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .panel h3 { margin-bottom: 16px; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<header class="header">
    <div class="logo"></i> Portal Colaborador</div>
    <div class="header-usuario">
        <span class="avatar-circulo">
            <?php if (!empty($_SESSION['colaborador_foto']) && $_SESSION['colaborador_foto'] !== 'default-avatar.png'): ?>
                <img src="/Mesa_Ayuda/public/uploads/avatars/<?= htmlspecialchars($_SESSION['colaborador_foto']) ?>" alt="Foto de perfil">
            <?php else: ?>
                <?= htmlspecialchars($_SESSION['colaborador_inicial'] ?? 'U') ?>
            <?php endif; ?>
        </span>
        <span class="nombre"><?= htmlspecialchars($nombre ?? 'Usuario') ?></span>
        <form method="POST" action="/Mesa_Ayuda/public/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" style="background:rgba(255,255,255,0.2);color:#fff;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;">Cerrar</button>
        </form>
    </div>
</header>

<nav class="nav-menu">
    <a href="/Mesa_Ayuda/public/dashboard" class="active"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/public/crear-ticket"><i class="bi bi-plus-circle"></i> Nuevo Ticket</a>
    <a href="/Mesa_Ayuda/public/mis-tickets"><i class="bi bi-clipboard-check"></i> Mis Tickets</a>
    <a href="/Mesa_Ayuda/public/perfil"><i class="bi bi-person-circle"></i> Mi Perfil</a>
</nav>

<div class="content">
    <div class="welcome">
        <h1></i> ¡Bienvenido, <?= htmlspecialchars($nombre ?? 'Usuario') ?>!</h1>
        <p>Aquí puedes gestionar tus tickets</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="cards">
        <div class="card"><div class="numero"><?= $totales['total'] ?? 0 ?></div><div class="label">Total Tickets</div></div>
        <div class="card"><div class="numero"><?= $totales['en_proceso'] ?? 0 ?></div><div class="label">En Proceso</div></div>
        <div class="card"><div class="numero"><?= $totales['culminados'] ?? 0 ?></div><div class="label">Culminados</div></div>
    </div>

    <div class="grid-2">
        <div class="panel">
            <h3></i> Mis Tickets Recientes</h3>
            <?php if (count($tickets) === 0): ?>
                <p style="color:#999;">No tienes tickets creados</p>
            <?php else: ?>
                <table>
                    <thead><tr><th>ID</th><th>Título</th><th>Estado</th><th>Acción</th></tr></thead>
                    <tbody>
                        <?php foreach (array_slice($tickets, 0, 5) as $t): ?>
                            <tr>
                                <td>#<?= $t['id'] ?></td>
                                <td><?= htmlspecialchars($t['titulo']) ?></td>
                                <td><span class="badge badge-<?= strtolower($t['estado']) ?>"><?= $t['estado'] ?></span></td>
                                <td><a href="/Mesa_Ayuda/public/ver-ticket/<?= $t['id'] ?>" class="btn btn-primary btn-sm" style="padding:4px 12px;font-size:12px;">Ver</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="panel">
            <h3></i> Acciones Rápidas</h3>
            <a href="/Mesa_Ayuda/public/crear-ticket" class="btn btn-success" style="display:block;margin-bottom:12px;"><i class="bi bi-plus-circle"></i> Nuevo Ticket</a>
            <a href="/Mesa_Ayuda/public/mis-tickets" class="btn btn-primary" style="display:block;margin-bottom:12px;"><i class="bi bi-clipboard-check"></i> Ver todos mis tickets</a>
            <a href="/Mesa_Ayuda/public/perfil" class="btn btn-primary" style="display:block;"><i class="bi bi-person-circle"></i> Mi Perfil</a>
        </div>
    </div>
</div>

</body>
</html>
