<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Satisfacción - Mesa de Ayuda</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

    <div class="container">
        <div class="card">

            <!-- ENCABEZADO -->
            <div class="header">
                <div class="brand">Mesa de Ayuda</div>
                <h1>Encuesta de Satisfacción</h1>
                <p>Tu opinión nos ayuda a mejorar</p>
            </div>

            <!-- ALERTAS -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- INFO DEL TICKET -->
            <div class="ticket-info">
                <div class="id">Ticket <span>#<?= $ticket['id'] ?></span></div>
                <div class="title"><?= htmlspecialchars($ticket['titulo']) ?></div>
                <div class="meta">
                    <?= htmlspecialchars($ticket['categoria_nombre'] ?? 'N/A') ?> ·
                    <?= date('d/m/Y H:i', strtotime($ticket['fecha_culminacion'])) ?>
                </div>
            </div>

            <!-- FORMULARIO -->
            <form method="POST" action="/Mesa_Ayuda/public/guardar-encuesta">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">

                <div class="form-group">
                    <label for="calificacion">Calificación</label>
                    <select name="calificacion" id="calificacion" required>
                        <option value="">Selecciona una opción</option>
                        <option value="Satisfecho">Satisfecho</option>
                        <option value="Conforme">Conforme</option>
                        <option value="Inconforme">Inconforme</option>
                        <option value="No respondida">No respondida</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="comentario">Comentario *</label>
                    <textarea name="comentario" id="comentario"
                              placeholder="¿Qué opinas del servicio recibido?"
                              required
                              oninvalid="this.setCustomValidity('Debes dejar un comentario antes de enviar la encuesta.')"
                              oninput="this.setCustomValidity('')"></textarea>
                </div>

                <button type="submit" class="btn">Enviar encuesta</button>
            </form>

            <a href="/Mesa_Ayuda/public/mis-tickets" class="back-link">← Volver a Mis Tickets</a>

        </div>
    </div>


<style>
   
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        position: relative;
        background: #0a1628;
    }

    /* ========== FONDO CON IMAGEN ========== */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            linear-gradient(145deg, rgba(10, 30, 61, 0.78), rgba(13, 43, 94, 0.85)),
            url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1600&q=80') center/cover no-repeat;
        z-index: 0;
    }

    /* ========== CONTENEDOR ========== */
    .container {
        position: relative;
        z-index: 1;
        max-width: 520px;
        width: 100%;
    }

    /* ========== TARJETA PRINCIPAL ========== */
    .card {
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        padding: 44px 40px 36px;
        border-radius: 20px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.06);
        animation: fadeUp 0.5s ease-out;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(32px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ========== ENCABEZADO ========== */
    .header {
        text-align: center;
        padding-bottom: 28px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        margin-bottom: 28px;
    }

    .header .brand {
        font-size: 13px;
        font-weight: 600;
        color: #0D47A1;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 4px;
    }

    .header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #0a1929;
        letter-spacing: -0.4px;
        line-height: 1.2;
    }

    .header p {
        font-size: 14px;
        color: #64748b;
        margin-top: 4px;
        font-weight: 400;
    }

    /* ========== ALERTAS ========== */
    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* ========== INFO DEL TICKET ========== */
    .ticket-info {
        background: #f8fafc;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid #eef2f6;
    }

    .ticket-info .id {
        font-weight: 700;
        color: #0a1929;
        font-size: 14px;
    }

    .ticket-info .id span {
        color: #0D47A1;
    }

    .ticket-info .title {
        font-size: 14px;
        color: #1e293b;
        margin-top: 2px;
    }

    .ticket-info .meta {
        font-size: 13px;
        color: #94a3b8;
        margin-top: 4px;
    }

    /* ========== FORMULARIO ========== */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
        margin-bottom: 6px;
        letter-spacing: 0.2px;
    }

    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 11px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        background: #ffffff;
        transition: all 0.2s;
        color: #0a1929;
        appearance: none;
    }

    .form-group select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234a5568' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
    }

    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0D47A1;
        box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.08);
    }

    .form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .form-group select::placeholder,
    .form-group textarea::placeholder {
        color: #94a3b8;
    }

    /* ========== BOTÓN ========== */
    .btn {
        width: 100%;
        padding: 14px;
        background: #0D47A1;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s;
        letter-spacing: 0.2px;
    }

    .btn:hover {
        background: #1565C0;
        box-shadow: 0 6px 24px rgba(13, 71, 161, 0.30);
        transform: translateY(-1px);
    }

    .btn:active {
        transform: scale(0.98);
    }

    /* ========== ENLACE ========== */
    .back-link {
        display: block;
        text-align: center;
        margin-top: 18px;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: color 0.2s;
        letter-spacing: 0.2px;
    }

    .back-link:hover {
        color: #ffffff;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 520px) {
        .card {
            padding: 28px 20px 24px;
            border-radius: 16px;
        }

        .header h1 {
            font-size: 20px;
        }

        .ticket-info {
            padding: 14px 16px;
        }

        .btn {
            font-size: 14px;
            padding: 13px;
        }
    }

    @media (max-width: 400px) {
        body {
            padding: 12px;
        }

        .card {
            padding: 20px 14px 18px;
            border-radius: 12px;
        }

        .header {
            padding-bottom: 18px;
            margin-bottom: 18px;
        }

        .header h1 {
            font-size: 18px;
        }
    }
</style>

</body>
</html>