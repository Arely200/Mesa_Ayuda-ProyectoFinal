<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta - Mesa de Ayuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { background: #fff; padding: 48px; border-radius: 16px; max-width: 500px; width: 100%; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        h1 { color: #0D47A1; margin-bottom: 8px; }
        .sub { color: #757575; margin-bottom: 24px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #212121; }
        .opciones { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .opcion { padding: 12px 16px; border: 2px solid #E0E0E0; border-radius: 8px; cursor: pointer; text-align: center; transition: all 0.3s; }
        .opcion:hover { border-color: #1E88E5; background: #E3F2FD; }
        .opcion input[type="radio"] { display: none; }
        .opcion.selected { border-color: #1E88E5; background: #E3F2FD; }
        .opcion .emoji { font-size: 28px; display: block; }
        .opcion .texto { font-size: 13px; color: #616161; margin-top: 4px; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-size: 15px; font-weight: 600; }
        .btn-primary { background: #1E88E5; color: #fff; width: 100%; }
        .btn-primary:hover { background: #1565C0; }
        .btn-secondary { background: #E0E0E0; color: #616161; width: 100%; margin-top: 8px; }
        .btn-secondary:hover { background: #BDBDBD; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
        .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<div class="container">
    <h1><i class="bi bi-pencil-square"></i> Encuesta de Satisfacción</h1>
    <p class="sub">Ticket #<?= $ticket['id'] ?>: <?= htmlspecialchars($ticket['titulo']) ?></p>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="POST" action="/Mesa_Ayuda/public/guardar-encuesta">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">

        <div class="form-group">
            <label>¿Cómo calificas la atención recibida?</label>
            <div class="opciones">
                <label class="opcion" onclick="seleccionar(this)">
                    <input type="radio" name="calificacion" value="Satisfecho" required>
                    <span class="emoji"><i class="bi bi-emoji-smile"></i></span>
                    <span class="texto">Satisfecho</span>
                </label>
                <label class="opcion" onclick="seleccionar(this)">
                    <input type="radio" name="calificacion" value="Conforme">
                    <span class="emoji"><i class="bi bi-emoji-neutral"></i></span>
                    <span class="texto">Conforme</span>
                </label>
                <label class="opcion" onclick="seleccionar(this)">
                    <input type="radio" name="calificacion" value="Inconforme">
                    <span class="emoji"><i class="bi bi-emoji-frown"></i></span>
                    <span class="texto">Inconforme</span>
                </label>
                <label class="opcion" onclick="seleccionar(this)">
                    <input type="radio" name="calificacion" value="No respondida">
                    <span class="emoji"><i class="bi bi-question-circle"></i></span>
                    <span class="texto">No respondida</span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label>Comentario (opcional)</label>
            <textarea name="comentario" rows="3" style="width:100%;padding:10px;border:2px solid #E0E0E0;border-radius:8px;font-family:inherit;resize:vertical;"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enviar Encuesta</button>
        <a href="/Mesa_Ayuda/public/mis-tickets" class="btn btn-secondary">Omitir</a>
    </form>
</div>

<script>
    function seleccionar(el) {
        document.querySelectorAll('.opcion').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input[type="radio"]').checked = true;
    }
</script>

</body>
</html>