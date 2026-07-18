<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Noticias — Mesa de Ayuda</title>
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

.noticias-wrap{max-width:820px; margin:0 auto; padding:88px 44px;}
.noticia{border:1px solid var(--borde); border-radius:18px; padding:34px; margin-bottom:24px; transition:border-color .2s ease, transform .2s ease;}
.noticia:hover{border-color:var(--celeste); transform:translateY(-3px);}
.noticia .fecha{font-family:var(--mono); font-size:12px; color:var(--azul-electrico); text-transform:uppercase; letter-spacing:0.05em;}
.noticia h2{font-family:var(--display); font-size:21px; font-weight:700; color:var(--marino); margin:10px 0 12px; letter-spacing:-0.01em;}
.noticia p{color:var(--texto-suave); line-height:1.65; font-size:14.5px; margin-bottom:10px;}
.noticia ul{margin:12px 0 4px 4px; list-style:none;}
.noticia ul li{
  color:var(--texto-suave); font-size:14px; line-height:1.7; padding-left:26px; position:relative;
}
.noticia ul li::before{
  content:''; position:absolute; left:0; top:7px; width:14px; height:14px;
  background:rgba(30,136,229,0.12); border-radius:50%;
}
.noticia ul li::after{
  content:''; position:absolute; left:4px; top:11px; width:6px; height:6px;
  border-left:1.6px solid var(--azul-electrico); border-bottom:1.6px solid var(--azul-electrico);
  transform:rotate(-45deg); top:10.5px;
}
.tag{display:inline-flex; align-items:center; background:rgba(30,136,229,0.08); color:var(--azul-electrico); font-family:var(--mono); padding:5px 13px; border-radius:14px; font-size:11.5px; margin-top:14px;}

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
  .noticias-wrap{padding:64px 22px;}
  .noticia{padding:24px;}
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
    <a href="/Mesa_Ayuda/noticias" class="activo">Noticias</a>
    <a href="/Mesa_Ayuda/about">Sobre Nosotros</a>
    <a href="/Mesa_Ayuda/contacto">Contacto</a>
    <a href="/Mesa_Ayuda/login" class="btn-login">Iniciar Sesión</a>
  </nav>
  <button class="nav-toggle" id="navToggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="navMenu">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
</header>

<section class="mini-hero">
  <div class="eyebrow">Al día</div>
  <h1>Noticias</h1>
  <p>Novedades, buenas prácticas y actualizaciones sobre Mesa de Ayuda.</p>
</section>

<div class="noticias-wrap">
  <div class="noticia reveal">
    <div class="fecha">27 de junio, 2026</div>
    <h2>¿Qué es una Mesa de Ayuda?</h2>
    <p>Una Mesa de Ayuda (Help Desk) es un sistema centralizado que permite gestionar solicitudes, incidentes y consultas de los usuarios. Su objetivo principal es mejorar la eficiencia en la atención al cliente interno y externo.</p>
    <p>Implementar una Mesa de Ayuda permite centralizar todas las solicitudes, asignar responsables, dar seguimiento y medir la satisfacción del usuario final.</p>
    <span class="tag">#MesaDeAyuda</span>
  </div>

  <div class="noticia reveal">
    <div class="fecha">25 de junio, 2026</div>
    <h2>Beneficios de la gestión de tickets</h2>
    <p>La gestión de tickets es fundamental para mantener un control efectivo de las incidencias. Algunos beneficios clave incluyen:</p>
    <ul>
      <li>Reducción de tiempos de respuesta</li>
      <li>Mayor satisfacción de los usuarios</li>
      <li>Historial completo de interacciones</li>
      <li>Medición de rendimiento de agentes</li>
    </ul>
    <span class="tag">#Tickets</span>
  </div>

  <div class="noticia reveal">
    <div class="fecha">20 de junio, 2026</div>
    <h2>Seguridad en sistemas de ayuda</h2>
    <p>La seguridad es un aspecto crítico en cualquier sistema de atención. Nuestro sistema Mesa de Ayuda implementa múltiples capas de seguridad:</p>
    <ul>
      <li>Contraseñas con longitud de 8 a 12 caracteres</li>
      <li>Bloqueo de cuentas tras 3 intentos fallidos</li>
      <li>Tokens CSRF para prevenir ataques</li>
      <li>Firmas digitales para proteger la integridad de los datos</li>
    </ul>
    <span class="tag">#Seguridad</span>
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