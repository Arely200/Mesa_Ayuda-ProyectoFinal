<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Mi Perfil') ?></title>
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
    <a href="/Mesa_Ayuda/public/mis-tickets"><i class="bi bi-clipboard-check"></i> Mis Tickets</a>
    <a href="/Mesa_Ayuda/public/perfil" class="active"><i class="bi bi-person-circle"></i> Mi Perfil</a>
</nav>


<div class="content">

    <!-- ALERTAS -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- PERFIL -->
    <div class="card">
        <h1>Mi Perfil</h1>
        <div class="perfil-grid" style="margin-top:24px;">
            <div>
                <div class="foto-actual">
                    <?php if (!empty($colaborador['foto_perfil']) && $colaborador['foto_perfil'] !== 'default-avatar.png'): ?>
                        <img src="/Mesa_Ayuda/public/uploads/avatars/<?= htmlspecialchars($colaborador['foto_perfil']) ?>" alt="Foto de perfil">
                    <?php else: ?>
                        <?= htmlspecialchars($_SESSION['colaborador_inicial'] ?? 'U') ?>
                    <?php endif; ?>
                </div>

                <form method="POST" action="/Mesa_Ayuda/public/perfil/foto" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <div class="form-group">
                        <label>Foto de perfil</label>
                        <input type="file" name="foto_perfil" accept="image/jpeg,image/png,image/gif,image/webp" required>
                        <small>JPG, PNG, GIF o WEBP. Máximo 2MB.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar foto</button>
                </form>
            </div>

            <div>
                <h2 style="margin-bottom:16px;color:#0D47A1;">Datos del colaborador</h2>
                <div class="datos">
                    <div class="dato"><label>Primer nombre</label><span><?= htmlspecialchars($colaborador['primer_nombre']) ?></span></div>
                    <div class="dato"><label>Segundo nombre</label><span><?= htmlspecialchars($colaborador['segundo_nombre'] ?? 'No registrado') ?></span></div>
                    <div class="dato"><label>Primer apellido</label><span><?= htmlspecialchars($colaborador['primer_apellido']) ?></span></div>
                    <div class="dato"><label>Segundo apellido</label><span><?= htmlspecialchars($colaborador['segundo_apellido'] ?? 'No registrado') ?></span></div>
                    <div class="dato"><label>Sexo</label><span><?= htmlspecialchars($colaborador['sexo']) ?></span></div>
                    <div class="dato"><label>Identificación</label><span><?= htmlspecialchars($colaborador['identificacion']) ?></span></div>
                    <div class="dato"><label>Fecha de nacimiento</label><span><?= htmlspecialchars($colaborador['fecha_nacimiento']) ?></span></div>
                    <div class="dato"><label>Correo</label><span><?= htmlspecialchars($colaborador['email']) ?></span></div>
                </div>
                <div style="margin-top:22px;">
                    <a href="/Mesa_Ayuda/public/dashboard" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>

    <!-- CAMBIAR CONTRASEÑA -->
    <div class="card">
        <h2 style="margin-bottom:16px;color:#0D47A1;">Cambiar contraseña</h2>
        <form method="POST" action="/Mesa_Ayuda/public/cambiar-password">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <div class="password-grid">
                <div class="form-group">
                    <label>Contraseña actual *</label>
                    <input type="password" name="password_actual" required>
                </div>
                <div class="form-group">
                    <label>Nueva contraseña *</label>
                    <input type="password" name="password_nueva" required minlength="8" maxlength="12">
                    <small>Entre 8 y 12 caracteres.</small>
                </div>
                <div class="form-group">
                    <label>Confirmar contraseña *</label>
                    <input type="password" name="password_confirmar" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
        </form>
    </div>
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
        max-width: 980px;
        margin: 0 auto;
    }

    /* ========== TARJETA ========== */
    .card {
        background: #fff;
        padding: 32px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    /* ========== PERFIL ========== */
    .perfil-grid {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 28px;
        align-items: start;
    }

    .foto-actual {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: #E3F2FD;
        color: #0D47A1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        font-weight: 700;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .foto-actual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ========== DATOS ========== */
    .datos {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .dato {
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .dato label {
        display: block;
        font-size: 12px;
        color: #757575;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .dato span {
        color: #212121;
    }

    /* ========== FORMULARIO ========== */
    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        color: #212121;
    }

    .form-group input {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #E0E0E0;
        border-radius: 6px;
        font-size: 14px;
    }

    .password-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    small {
        color: #757575;
        font-size: 12px;
        display: block;
        margin-top: 6px;
    }

    /* ========== BOTONES ========== */
    .btn {
        padding: 12px 24px;
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

    .alert-error {
        background: #FFEBEE;
        color: #C62828;
        border: 1px solid #FFCDD2;
    }

    .alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        border: 1px solid #C8E6C9;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .perfil-grid {
            grid-template-columns: 1fr;
        }

        .datos {
            grid-template-columns: 1fr;
        }

        .password-grid {
            grid-template-columns: 1fr;
        }

        .content {
            padding: 16px;
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

        .nav-menu a {
            padding: 12px 14px;
            font-size: 13px;
            white-space: nowrap;
        }

        .foto-actual {
            width: 120px;
            height: 120px;
            font-size: 40px;
        }
    }
</style>

</body>
</html>