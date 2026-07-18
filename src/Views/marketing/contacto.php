<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contacto — Mesa de Ayuda</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --marino:#0B2A5B; --azul-real:#0D47A1; --azul-electrico:#1E88E5;
  --celeste:#64B5F6; --niebla:#F4F7FB; --blanco:#FFFFFF;
  --texto:#17233F; --texto-suave:#5B6B85; --borde:#E3E8F0;
  --display:'Sora',sans-serif; --cuerpo:'Inter',sans-serif; --mono:'JetBrains Mono',monospace;
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{font-family:var(--cuerpo); color:var(--texto); background:var(--blanco); -webkit-font-smoothing:antialiased; overflow-x:hidden;}
a{color:inherit;}

.header{position:sticky; top:0; z-index:100; display:flex; justify-content:space-between; align-items:center; padding:18px 44px; background:rgba(11,42,91,0.86); backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px); border-bottom:1px solid rgba(255,255,255,0.08);}
.logo{font-family:var(--display); font-weight:800; font-size:20px; color:#fff; letter-spacing:-0.02em;}
.logo span{color:var(--celeste);}
.nav{display:flex; align-items:center; gap:34px;}
.nav a{color:rgba(255,255,255,0.82); text-decoration:none; font-size:14.5px; font-weight:500; position:relative; padding:4px 0;}
.nav a:not(.btn-login)::after{content:''; position:absolute; left:0; bottom:-2px; width:0; height:1.5px; background:var(--celeste); transition:width .2s ease;}
.nav a:not(.btn-login):hover::after{width:100%;}
.nav a:hover{color:#fff;}
.nav a.activo{color:#fff;}
.nav a.activo::after{width:100%;}
.btn-login{border:1.5px solid rgba(255,255,255,0.5); padding:9px 22px; border-radius:8px; font-weight:600; transition:.2s ease;}
.btn-login:hover{background:#fff; color:var(--marino) !important; border-color:#fff;}

.nav-toggle{
  display:none;
  background:none;
  border:none;
  color:#fff;
  cursor:pointer;
  padding:6px;
  align-items:center;
  justify-content:center;
}
.nav-toggle svg{display:block;}


.mini-hero{background:linear-gradient(180deg, var(--marino) 0%, #0C3572 62%, var(--azul-real) 100%); padding:76px 44px 84px; text-align:center; position:relative; overflow:hidden;}
.mini-hero::before{content:''; position:absolute; width:600px; height:600px; border-radius:50%; background:radial-gradient(circle, rgba(100,181,246,0.14) 0%, transparent 70%); top:-260px; left:50%; transform:translateX(-50%); pointer-events:none;}
.eyebrow{display:inline-flex; align-items:center; gap:8px; font-family:var(--mono); font-size:12.5px; color:var(--celeste); background:rgba(100,181,246,0.12); border:1px solid rgba(100,181,246,0.3); padding:6px 14px; border-radius:20px; margin-bottom:22px; position:relative; z-index:1;}
.mini-hero h1{font-family:var(--display); font-size:42px; font-weight:800; color:#fff; letter-spacing:-0.02em; margin-bottom:16px; position:relative; z-index:1;}
.mini-hero p{font-size:16.5px; color:rgba(227,234,245,0.82); max-width:520px; margin:0 auto; line-height:1.6; position:relative; z-index:1;}

.contacto-wrap{max-width:1080px; margin:0 auto; padding:88px 44px; display:grid; grid-template-columns:1.2fr 0.8fr; gap:48px; align-items:start;}

.form-card{background:var(--niebla); border:1px solid var(--borde); border-radius:18px; padding:38px;}
.form-card .kicker{font-family:var(--mono); font-size:12px; color:var(--azul-electrico); text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:8px;}
.form-card h2{font-family:var(--display); font-size:24px; font-weight:800; color:var(--marino); margin-bottom:26px; letter-spacing:-0.01em;}
.form-group{margin-bottom:20px;}
.form-group label{display:block; font-weight:600; margin-bottom:7px; color:var(--marino); font-size:13.5px;}
.form-group input, .form-group textarea{
  width:100%; padding:12px 15px; border:1.5px solid var(--borde); border-radius:9px;
  font-size:14px; font-family:var(--cuerpo); background:#fff; transition:border-color .2s ease, box-shadow .2s ease;
}
.form-group input:focus, .form-group textarea:focus{outline:none; border-color:var(--azul-electrico); box-shadow:0 0 0 4px rgba(30,136,229,0.12);}
.form-group textarea{min-height:120px; resize:vertical;}
.btn-primario{
  padding:14px 30px; background:var(--azul-electrico); color:#fff; border:none; border-radius:9px;
  cursor:pointer; font-size:14.5px; font-weight:700; box-shadow:0 12px 28px -8px rgba(30,136,229,0.5);
  transition:transform .18s ease, box-shadow .18s ease;
}
.btn-primario:hover{transform:translateY(-2px); box-shadow:0 16px 34px -8px rgba(30,136,229,0.6);}

.info-col{display:flex; flex-direction:column; gap:16px;}
.info-item{border:1px solid var(--borde); border-radius:14px; padding:22px; display:flex; gap:14px; align-items:flex-start; transition:border-color .2s ease;}
.info-item:hover{border-color:var(--celeste);}
.info-item .icon-wrap{width:40px; height:40px; min-width:40px; border-radius:10px; background:rgba(30,136,229,0.1); color:var(--azul-electrico); display:flex; align-items:center; justify-content:center;}
.info-item .label{font-family:var(--display); font-weight:700; font-size:14.5px; color:var(--marino); margin-bottom:3px;}
.info-item .valor{color:var(--texto-suave); font-size:13.5px;}

.horario-card{background:linear-gradient(155deg, var(--marino), var(--azul-real)); border-radius:14px; padding:22px; color:#fff;}
.horario-card .titulo{font-family:var(--mono); font-size:11.5px; text-transform:uppercase; letter-spacing:0.06em; color:var(--celeste); margin-bottom:12px;}
.horario-row{display:flex; justify-content:space-between; font-size:13.5px; padding:6px 0; border-bottom:1px solid rgba(255,255,255,0.1);}
.horario-row:last-child{border-bottom:none;}
.horario-row .dia{color:rgba(227,234,245,0.75);}
.horario-row .hora{font-family:var(--mono); color:#fff;}

.footer{background:var(--marino); color:#fff; padding:20px 44px 28px;}
.footer-links{display:flex; justify-content:center; gap:28px; padding-bottom:20px; margin-bottom:20px; border-bottom:1px solid rgba(255,255,255,0.1); font-size:13.5px;}
.footer-links a{text-decoration:none; color:rgba(227,234,245,0.75);}
.footer-links a:hover{color:#fff;}
.footer-bottom{text-align:center; font-size:13px; color:rgba(227,234,245,0.55);}

.reveal{opacity:0; transform:translateY(24px); transition:opacity .7s ease, transform .7s ease;}
.reveal.visible{opacity:1; transform:translateY(0);}

@media(max-width:900px){
  .header{padding:14px 20px;}
  .nav-toggle{display:flex;}
  .nav{
    display:none;
    position:absolute;
    top:100%; left:0; right:0;
    background:var(--marino);
    flex-direction:column;
    align-items:stretch;
    gap:2px;
    padding:14px 20px 20px;
    border-bottom:1px solid rgba(255,255,255,0.12);
    box-shadow:0 16px 30px -10px rgba(0,0,0,0.4);
  }
  .nav.abierto{display:flex;}
  .nav a{padding:12px 4px; font-size:15.5px;}
  .btn-login{margin-top:8px; text-align:center;}
  .mini-hero{padding:56px 22px 64px;} .mini-hero h1{font-size:30px;}
  .contacto-wrap{grid-template-columns:1fr; padding:64px 22px;}
  .form-card{padding:26px;}
}
:focus-visible{outline:2px solid var(--celeste); outline-offset:3px;}
</style>
<link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<header class="header">
  <div class="logo">Mesa de <span>Ayuda</span></div>
  <nav class="nav" id="navMenu">
    <a href="/Mesa_Ayuda/">Inicio</a>
    <a href="/Mesa_Ayuda/noticias">Noticias</a>
    <a href="/Mesa_Ayuda/about">Sobre Nosotros</a>
    <a href="/Mesa_Ayuda/contacto" class="activo">Contacto</a>
    <a href="/Mesa_Ayuda/login" class="btn-login">Iniciar Sesión</a>
  </nav>
  <button class="nav-toggle" id="navToggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="navMenu">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
</header>

<section class="mini-hero">
  <div class="eyebrow">Hablemos</div>
  <h1>¿Tienes preguntas?</h1>
  <p>Escríbenos y te respondemos a la brevedad.</p>
</section>

<div class="contacto-wrap">
  <div class="form-card reveal">
    <span class="kicker">Formulario de contacto</span>
    <h2>Envíanos un mensaje</h2>
    <form method="POST" action="/Mesa_Ayuda/contacto/enviar">
      <div class="form-group">
        <label>Nombre Completo</label>
        <input type="text" name="nombre" placeholder="Tu nombre" required>
      </div>
      <div class="form-group">
        <label>Correo Electrónico</label>
        <input type="email" name="email" placeholder="tu@email.com" required>
      </div>
      <div class="form-group">
        <label>Asunto</label>
        <input type="text" name="asunto" placeholder="Asunto del mensaje" required>
      </div>
      <div class="form-group">
        <label>Mensaje</label>
        <textarea name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>
      </div>
      <button type="submit" class="btn-primario">Enviar Mensaje</button>
    </form>
  </div>

  <div class="info-col reveal">
    <div class="info-item">
      <div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg></div>
      <div>
        <div class="label">Email</div>
        <div class="valor">soporte@mesaayuda.com</div>
      </div>
    </div>
    <div class="info-item">
      <div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3 19.5 19.5 0 01-6-6 19.8 19.8 0 01-3-8.7A2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .3 2 .6 3a2 2 0 01-.5 2L8 10a16 16 0 006 6l1.3-1.2a2 2 0 012-.5c1 .3 2 .5 3 .6a2 2 0 011.7 2z"/></svg></div>
      <div>
        <div class="label">Teléfono</div>
        <div class="valor">+507 1234-5678</div>
      </div>
    </div>
    <div class="info-item">
      <div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.3 7-11.5A7 7 0 105 9.5C5 14.7 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg></div>
      <div>
        <div class="label">Ubicación</div>
        <div class="valor">Panamá, Panamá</div>
      </div>
    </div>

    <div class="horario-card">
      <div class="titulo">Horario de atención</div>
      <div class="horario-row"><span class="dia">Lunes – Viernes</span><span class="hora">8:00 – 17:00</span></div>
      <div class="horario-row"><span class="dia">Sábados</span><span class="hora">9:00 – 12:00</span></div>
      <div class="horario-row"><span class="dia">Domingos</span><span class="hora">Cerrado</span></div>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="footer-links">
    <a href="/Mesa_Ayuda/">Inicio</a>
    <a href="/Mesa_Ayuda/noticias">Noticias</a>
    <a href="/Mesa_Ayuda/about">Sobre Nosotros</a>
    <a href="/Mesa_Ayuda/contacto">Contacto</a>
  </div>
  <div class="footer-bottom">&copy; 2026 Mesa de Ayuda — Sistema de Gestión de Tickets</div>
</footer>

<script>
const reveals = document.querySelectorAll('.reveal');
const io = new IntersectionObserver((entries) => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
}, { threshold: 0.15 });
reveals.forEach(el => io.observe(el));

// Menú móvil (hamburguesa)
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');
navToggle.addEventListener('click', () => {
  const abierto = navMenu.classList.toggle('abierto');
  navToggle.setAttribute('aria-expanded', abierto);
});
navMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
  navMenu.classList.remove('abierto');
  navToggle.setAttribute('aria-expanded', 'false');
}));
</script>

</body>
</html>