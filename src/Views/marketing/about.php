<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sobre Nosotros — Mesa de Ayuda</title>
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


.mini-hero{
  background:linear-gradient(180deg, var(--marino) 0%, #0C3572 62%, var(--azul-real) 100%);
  padding:76px 44px 84px; text-align:center; position:relative; overflow:hidden;
}
.mini-hero::before{content:''; position:absolute; width:600px; height:600px; border-radius:50%; background:radial-gradient(circle, rgba(100,181,246,0.14) 0%, transparent 70%); top:-260px; left:50%; transform:translateX(-50%); pointer-events:none;}
.eyebrow{display:inline-flex; align-items:center; gap:8px; font-family:var(--mono); font-size:12.5px; color:var(--celeste); background:rgba(100,181,246,0.12); border:1px solid rgba(100,181,246,0.3); padding:6px 14px; border-radius:20px; margin-bottom:22px; position:relative; z-index:1;}
.mini-hero h1{font-family:var(--display); font-size:42px; font-weight:800; color:#fff; letter-spacing:-0.02em; margin-bottom:16px; position:relative; z-index:1;}
.mini-hero p{font-size:16.5px; color:rgba(227,234,245,0.82); max-width:520px; margin:0 auto; line-height:1.6; position:relative; z-index:1;}

.section{padding:88px 44px;}
.section-inner{max-width:1100px; margin:0 auto;}
.section-head{max-width:640px; margin:0 auto 48px; text-align:center;}
.kicker{font-family:var(--mono); font-size:12.5px; color:var(--azul-electrico); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:14px; display:block;}
.section-head h2{font-family:var(--display); font-size:32px; font-weight:800; color:var(--marino); letter-spacing:-0.02em; margin-bottom:12px;}
.section-head p{font-size:15.5px; color:var(--texto-suave); line-height:1.6;}

.mv-grid{display:grid; grid-template-columns:1fr 1fr; gap:22px; margin-bottom:22px;}
.mv-card{border-radius:18px; padding:36px;}
.mv-card.oscura{background:linear-gradient(155deg, var(--marino), var(--azul-real)); color:#fff;}
.mv-card.clara{background:var(--niebla); border:1px solid var(--borde);}
.mv-card .icon-wrap{width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:20px;}
.mv-card.oscura .icon-wrap{background:rgba(255,255,255,0.12); color:var(--celeste);}
.mv-card.clara .icon-wrap{background:rgba(30,136,229,0.1); color:var(--azul-electrico);}
.mv-card h3{font-family:var(--display); font-size:20px; font-weight:700; margin-bottom:12px;}
.mv-card.clara h3{color:var(--marino);}
.mv-card p{font-size:14.5px; line-height:1.65;}
.mv-card.oscura p{color:rgba(227,234,245,0.85);}
.mv-card.clara p{color:var(--texto-suave);}

.valores-grid{display:grid; grid-template-columns:repeat(4,1fr); gap:20px;}
.valor-item{border:1px solid var(--borde); border-radius:14px; padding:26px 22px; transition:border-color .2s ease, transform .2s ease;}
.valor-item:hover{border-color:var(--celeste); transform:translateY(-3px);}
.valor-item .icon-wrap{width:40px; height:40px; border-radius:10px; background:rgba(11,42,91,0.06); color:var(--marino); display:flex; align-items:center; justify-content:center; margin-bottom:16px;}
.valor-item h4{font-family:var(--display); font-size:15px; font-weight:700; color:var(--marino); margin-bottom:8px;}
.valor-item p{font-size:13.3px; color:var(--texto-suave); line-height:1.55;}

.equipo{background:var(--niebla); padding:88px 44px;}
.equipo-grid{max-width:900px; margin:40px auto 0; display:grid; grid-template-columns:repeat(3,1fr); gap:22px;}
.miembro{background:#fff; border:1px solid var(--borde); border-radius:16px; padding:32px 20px; text-align:center; transition:transform .2s ease, box-shadow .2s ease;}
.miembro:hover{transform:translateY(-4px); box-shadow:0 20px 40px -20px rgba(11,42,91,0.18);}
.miembro .avatar{width:64px; height:64px; border-radius:50%; background:linear-gradient(155deg, var(--azul-electrico), var(--marino)); display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-family:var(--display); font-size:20px; font-weight:700; color:#fff;}
.miembro h4{font-family:var(--display); color:var(--marino); font-size:15.5px; font-weight:700; margin-bottom:5px;}
.miembro p{font-family:var(--mono); color:var(--texto-suave); font-size:12px;}

.cta-final{margin:0 44px 100px; background:linear-gradient(135deg, var(--marino), var(--azul-real) 60%, #123a82); border-radius:22px; padding:64px 60px; text-align:center; position:relative; overflow:hidden;}
.cta-final::after{content:''; position:absolute; width:500px; height:500px; border-radius:50%; background:radial-gradient(circle, rgba(100,181,246,0.18), transparent 70%); bottom:-260px; left:50%; transform:translateX(-50%);}
.cta-final h2{font-family:var(--display); font-size:28px; font-weight:800; color:#fff; margin-bottom:14px; position:relative; z-index:1;}
.cta-final p{color:rgba(227,234,245,0.82); font-size:15.5px; margin-bottom:28px; position:relative; z-index:1;}
.btn-primario{padding:14px 28px; background:var(--azul-electrico); color:#fff; text-decoration:none; border-radius:9px; font-weight:700; font-size:14.5px; box-shadow:0 12px 28px -8px rgba(30,136,229,0.55); transition:transform .18s ease; display:inline-block; position:relative; z-index:1;}
.btn-primario:hover{transform:translateY(-2px);}

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
  .section, .equipo{padding:64px 22px;}
  .mv-grid{grid-template-columns:1fr;}
  .valores-grid{grid-template-columns:1fr 1fr;}
  .equipo-grid{grid-template-columns:1fr;}
  .cta-final{margin:0 20px 72px; padding:48px 26px;}
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
    <a href="/Mesa_Ayuda/about" class="activo">Sobre Nosotros</a>
    <a href="/Mesa_Ayuda/contacto">Contacto</a>
    <a href="/Mesa_Ayuda/login" class="btn-login">Iniciar Sesión</a>
  </nav>
  <button class="nav-toggle" id="navToggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="navMenu">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
</header>

<section class="mini-hero">
  <div class="eyebrow">Quiénes somos</div>
  <h1>El equipo detrás de Mesa de Ayuda</h1>
  <p>Construimos una herramienta para que ninguna solicitud interna se pierda en el camino.</p>
</section>

<section class="section">
  <div class="section-inner">
    <div class="mv-grid">
      <div class="mv-card oscura reveal">
        <div class="icon-wrap">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3 6 6 1-4.5 4.5L18 20l-6-3-6 3 1.5-6.5L3 9l6-1 3-6z"/></svg>
        </div>
        <h3>Misión</h3>
        <p>Facilitar la gestión de solicitudes e incidentes a través de una plataforma intuitiva y eficiente, mejorando la comunicación entre colaboradores y el equipo de soporte técnico.</p>
      </div>
      <div class="mv-card clara reveal">
        <div class="icon-wrap">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <h3>Visión</h3>
        <p>Ser la plataforma líder en gestión de tickets para organizaciones, ofreciendo soluciones innovadoras que optimicen la atención al cliente interno y externo.</p>
      </div>
    </div>

    <div class="section-head reveal" style="margin-top:64px;">
      <span class="kicker">Lo que nos guía</span>
      <h2>Nuestros valores</h2>
    </div>
    <div class="valores-grid">
      <div class="valor-item reveal">
        <div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 21h4M12 3a6 6 0 00-4 10.4c.6.6 1 1.4 1 2.3v.3h6v-.3c0-.9.4-1.7 1-2.3A6 6 0 0012 3z"/></svg></div>
        <h4>Innovación</h4>
        <p>Siempre buscando mejorar nuestros sistemas.</p>
      </div>
      <div class="valor-item reveal">
        <div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg></div>
        <h4>Calidad</h4>
        <p>Comprometidos con la excelencia en cada detalle.</p>
      </div>
      <div class="valor-item reveal">
        <div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/></svg></div>
        <h4>Seguridad</h4>
        <p>Protegiendo la integridad de los datos de nuestros usuarios.</p>
      </div>
      <div class="valor-item reveal">
        <div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <h4>Eficiencia</h4>
        <p>Optimizando procesos para una atención más rápida.</p>
      </div>
    </div>
  </div>
</section>

<section class="equipo">
  <div class="section-head reveal">
    <span class="kicker">El equipo</span>
    <h2>Personas detrás del proyecto</h2>
  </div>
  <div class="equipo-grid">
    <div class="miembro reveal">
      <div class="avatar">A</div>
      <h4>Arely</h4>
      <p>desarrolladora full stack</p>
    </div>
    <div class="miembro reveal">
      <div class="avatar">I2</div>
      <h4>Integrante 2</h4>
      <p>desarrollador backend</p>
    </div>
    <div class="miembro reveal">
      <div class="avatar">I3</div>
      <h4>Integrante 3</h4>
      <p>desarrollador frontend</p>
    </div>
  </div>
</section>

<div class="cta-final reveal">
  <h2>¿Listo para organizar tus solicitudes?</h2>
  <p>Crea tu cuenta y empieza a usar Mesa de Ayuda hoy mismo.</p>
  <a href="/Mesa_Ayuda/login" class="btn-primario">Comenzar ahora</a>
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