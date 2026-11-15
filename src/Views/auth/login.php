<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Iniciar Sesión' ?> - Mesa de Ayuda</title>
</head>
<body>

    <div class="login-wrapper">

        <!--LADO IZQUIERDO-->
        <div class="login-image">
            <div class="brand">
                <h1>Mesa de <span>Ayuda</span></h1>
                <p>Gestión inteligente de tickets</p>
                <div class="line"></div>
            </div>

            <div class="features">
                <div class="item">
                    <div class="icon">✓</div>
                    <span>Centraliza todas las solicitudes</span>
                </div>
                <div class="item">
                    <div class="icon">✓</div>
                    <span>Trazabilidad completa de tickets</span>
                </div>
                <div class="item">
                    <div class="icon">✓</div>
                    <span>Reportes y estadísticas en tiempo real</span>
                </div>
            </div>
        </div>

        <!-- ====== LADO DERECHO ====== -->
        <div class="login-form">

            <div class="header">
                <h2>Bienvenido</h2>
                <p>Inicia sesión para acceder al sistema</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">⚠️ <?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">✓ <?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/login">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?? '' ?>">

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@mesaayuda.com" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8" maxlength="12">
                    <small>Debe tener entre 8 y 12 caracteres</small>
                </div>

                <button type="submit" class="btn-primary">Iniciar sesión</button>
            </form>

            <div class="login-footer">
                ¿Olvidaste tu contraseña? <a href="mailto:soporte@mesaayuda.com">soporte@mesaayuda.com</a>
            </div>

            <div class="version">v1.0</div>
        </div>

    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const p = document.getElementById('password');
            if (p.value.length < 8 || p.value.length > 12) {
                e.preventDefault();
                alert('La contraseña debe tener entre 8 y 12 caracteres');
            }
        });
    </script>

    <style>
        /* ========== RESET ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            background: #0a1e3d;
        }

        /* ========== FONDO CON IMAGEN - MÁS VISIBLE ========== */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                /* Overlay más suave para que se vea la imagen */
                linear-gradient(135deg, rgba(10, 30, 61, 0.40), rgba(13, 43, 94, 0.50)),
                url(https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1600&q=80) center/cover no-repeat;
            z-index: 0;
        }

        /* ========== CONTENEDOR PRINCIPAL ========== */
        .login-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            max-width: 1000px;
            width: 100%;
            min-height: 560px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(2px);
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        /* ========== LADO IZQUIERDO ========== */
        .login-image {
            flex: 1;
            background: linear-gradient(145deg, #0a1e3d, #0d2b5e);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
            position: relative;
            min-height: 480px;
        }

        .login-image .brand {
            text-align: center;
            color: #fff;
        }

        .login-image .brand h1 {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .login-image .brand h1 span {
            color: #64b5f6;
        }

        .login-image .brand p {
            font-size: 15px;
            color: #90caf9;
            margin-top: 8px;
            font-weight: 300;
            letter-spacing: 0.3px;
        }

        .login-image .brand .line {
            width: 60px;
            height: 3px;
            background: #64b5f6;
            margin: 20px auto 0;
            border-radius: 4px;
        }

        .login-image .features {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            max-width: 280px;
        }

        .login-image .features .item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #e3f2fd;
            font-size: 14px;
        }

        .login-image .features .item .icon {
            width: 32px;
            height: 32px;
            background: rgba(100, 181, 246, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        /* ========== LADO DERECHO ========== */
        .login-form {
            flex: 1;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .login-form .header {
            margin-bottom: 32px;
        }

        .login-form .header h2 {
            font-size: 26px;
            font-weight: 700;
            color: #0a1e3d;
            letter-spacing: -0.3px;
        }

        .login-form .header p {
            color: #64748b;
            font-size: 14px;
            margin-top: 6px;
        }

        /* ========== ALERTAS ========== */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        /* ========== FORM ========== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s;
            background: #f8fafc;
            color: #0a1e3d;
        }

        .form-group input::placeholder {
            color: #94a3b8;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(26, 86, 219, 0.08);
        }

        .form-group small {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: #94a3b8;
        }

        /* ========== BOTÓN ========== */
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: #0a1e3d;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s;
            font-family: inherit;
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            background: #0d2b5e;
            box-shadow: 0 8px 24px rgba(10, 30, 61, 0.2);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        /* ========== FOOTER ========== */
        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
        }

        .login-footer a {
            color: #1a56db;
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .version {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #cbd5e1;
            letter-spacing: 0.5px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 820px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 440px;
                min-height: auto;
                border-radius: 16px;
            }

            .login-image {
                padding: 32px 24px;
                min-height: 200px;
            }

            .login-image .brand h1 {
                font-size: 28px;
            }

            .login-image .features {
                display: none;
            }

            .login-form {
                padding: 32px 24px;
            }
        }

        @media (max-width: 480px) {
            .login-form {
                padding: 24px 18px;
            }

            .login-form .header h2 {
                font-size: 22px;
            }
        }
    </style>

</body>
</html>