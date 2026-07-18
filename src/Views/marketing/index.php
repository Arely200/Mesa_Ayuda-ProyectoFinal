<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mesa de Ayuda — Gestión de Tickets</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --marino:#0B2A5B;
  --azul-real:#0D47A1;
  --azul-electrico:#1E88E5;
  --celeste:#64B5F6;
  --niebla:#F4F7FB;
  --blanco:#FFFFFF;
  --texto:#17233F;
  --texto-suave:#5B6B85;
  --borde:#E3E8F0;

  --display: 'Sora', sans-serif;
  --cuerpo: 'Inter', sans-serif;
  --mono: 'JetBrains Mono', monospace;
}

*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{
  font-family:var(--cuerpo);
  color:var(--texto);
  background:var(--blanco);
  -webkit-font-smoothing:antialiased;
  overflow-x:hidden;
}

a{color:inherit;}

/* ============ HEADER ============ */
.header{
  position:sticky; top:0; z-index:100;
  display:flex; justify-content:space-between; align-items:center;
  padding:18px 44px;
  background:rgba(11,42,91,0.86);
  backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);
  border-bottom:1px solid rgba(255,255,255,0.08);
  transition:padding .25s ease;
}
.logo{
  font-family:var(--display);
  font-weight:800;
  font-size:20px;
  color:#fff;
  letter-spacing:-0.02em;
}
.logo span{color:var(--celeste);}
.nav{display:flex; align-items:center; gap:34px;}
.nav a{
  color:rgba(255,255,255,0.82);
  text-decoration:none;
  font-size:14.5px;
  font-weight:500;
  position:relative;
  padding:4px 0;
}
.nav a:not(.btn-login)::after{
  content:'';
  position:absolute; left:0; bottom:-2px;
  width:0; height:1.5px; background:var(--celeste);
  transition:width .2s ease;
}
.nav a:not(.btn-login):hover::after{width:100%;}
.nav a:hover{color:#fff;}
.btn-login{
  border:1.5px solid rgba(255,255,255,0.5);
  padding:9px 22px; border-radius:8px;
  font-weight:600;
  transition:.2s ease;
}
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

/* ============ HERO ============ */
.hero{
  background:linear-gradient(180deg, var(--marino) 0%, #0C3572 62%, var(--azul-real) 100%);
  padding:96px 44px 120px;
  display:grid;
  grid-template-columns:1.05fr 1fr;
  gap:60px;
  align-items:center;
  position:relative;
  overflow:hidden;
}
.hero::before{
  content:'';
  position:absolute;
  width:640px; height:640px;
  border-radius:50%;
  background:radial-gradient(circle, rgba(100,181,246,0.16) 0%, transparent 70%);
  top:-220px; right:-160px;
  pointer-events:none;
}
.hero-copy{position:relative; z-index:2;}
.eyebrow{
  display:inline-flex; align-items:center; gap:8px;
  font-family:var(--mono);
  font-size:12.5px;
  color:var(--celeste);
  background:rgba(100,181,246,0.12);
  border:1px solid rgba(100,181,246,0.3);
  padding:6px 14px;
  border-radius:20px;
  margin-bottom:26px;
  letter-spacing:0.02em;
}
.eyebrow .dot{width:6px; height:6px; border-radius:50%; background:var(--celeste); animation:pulse 2s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:.35;}}

.hero h1{
  font-family:var(--display);
  font-size:52px;
  line-height:1.08;
  font-weight:800;
  color:#fff;
  letter-spacing:-0.02em;
  margin-bottom:22px;
}
.hero h1 em{
  font-style:normal;
  background:linear-gradient(90deg, var(--celeste), #9FD3FF);
  -webkit-background-clip:text;
  background-clip:text;
  color:transparent;
}
.hero p.sub{
  font-size:17.5px;
  line-height:1.65;
  color:rgba(227,234,245,0.86);
  max-width:480px;
  margin-bottom:36px;
}
.hero-ctas{display:flex; gap:14px; margin-bottom:52px;}
.btn-primario{
  padding:15px 30px;
  background:var(--azul-electrico);
  color:#fff;
  text-decoration:none;
  border-radius:9px;
  font-weight:700;
  font-size:15px;
  box-shadow:0 12px 28px -8px rgba(30,136,229,0.55);
  transition:transform .18s ease, box-shadow .18s ease;
  border:none;
}
.btn-primario:hover{transform:translateY(-2px); box-shadow:0 16px 34px -8px rgba(30,136,229,0.65);}
.btn-secundario{
  padding:15px 28px;
  background:transparent;
  border:1.5px solid rgba(255,255,255,0.28);
  color:#fff;
  text-decoration:none;
  border-radius:9px;
  font-weight:600;
  font-size:15px;
  transition:.2s ease;
}
.btn-secundario:hover{background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.5);}

.hero-meta{
  display:flex; gap:30px;
  padding-top:22px;
  border-top:1px solid rgba(255,255,255,0.1);
  max-width:480px;
  opacity:0.8;
}
.hero-meta .item{}
.hero-meta .item .label{
  font-family:var(--mono);
  font-size:10.5px;
  color:rgba(227,234,245,0.5);
  text-transform:uppercase;
  letter-spacing:0.06em;
  margin-bottom:4px;
}
.hero-meta .item .valor{
  font-family:var(--cuerpo);
  font-weight:600;
  font-size:13px;
  color:rgba(255,255,255,0.85);
}

/* ============ SCROLL CUE ============ */
.scroll-cue{
  position:absolute;
  bottom:28px; left:44px;
  z-index:3;
  display:flex; align-items:center; gap:10px;
  color:rgba(227,234,245,0.6);
  text-decoration:none;
  font-family:var(--mono);
  font-size:11.5px;
  letter-spacing:0.04em;
  cursor:pointer;
  transition:color .2s ease;
}
.scroll-cue:hover{color:#fff;}
.scroll-cue .flecha{
  width:30px; height:30px;
  border:1px solid rgba(255,255,255,0.25);
  border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  animation:bajar 1.8s ease-in-out infinite;
}
@keyframes bajar{
  0%,100%{ transform:translateY(0); }
  50%{ transform:translateY(5px); }
}
@media (prefers-reduced-motion: reduce){
  .scroll-cue .flecha{animation:none;}
}
@media(max-width:900px){
  .scroll-cue{display:none;}
}

/* ============ KANBAN VISUAL (SIGNATURE ELEMENT) ============ */
.kanban-wrap{
  position:relative; z-index:2;
}
.kanban{
  background:rgba(255,255,255,0.05);
  border:1px solid rgba(255,255,255,0.12);
  border-radius:18px;
  padding:20px;
  backdrop-filter:blur(6px);
  box-shadow:0 40px 80px -30px rgba(0,0,0,0.5);
  overflow:hidden;
}
.kanban-head{
  display:flex; justify-content:space-between; align-items:center;
  margin-bottom:16px; padding:0 4px;
}
.kanban-head .titulo{
  font-family:var(--mono);
  font-size:12px;
  color:rgba(227,234,245,0.6);
}
.kanban-head .puntos{display:flex; gap:6px;}
.kanban-head .puntos span{width:8px; height:8px; border-radius:50%; background:rgba(255,255,255,0.2);}
.kanban-cols{display:flex; flex-direction:column; gap:22px;}
.kanban-row-head{
  font-family:var(--mono);
  font-size:11px;
  font-weight:500;
  color:rgba(227,234,245,0.55);
  text-transform:uppercase;
  letter-spacing:0.04em;
  margin-bottom:10px;
  padding-left:2px;
  display:flex; align-items:center; gap:6px;
}
.kanban-row-head .bola{width:6px; height:6px; border-radius:50%;}
.fila-nuevo .bola{background:#FFB74D;}
.fila-progreso .bola{background:var(--celeste);}
.fila-resuelto .bola{background:#66D19E;}
.kanban-row-tickets{display:flex; gap:12px; flex-wrap:wrap;}
.kanban-row-tickets .ticket{flex:1 1 190px; min-width:170px; margin-bottom:0;}

.ticket{
  background:rgba(255,255,255,0.07);
  border:1px solid rgba(255,255,255,0.1);
  border-radius:10px;
  padding:12px 13px;
  margin-bottom:10px;
  transition:transform .5s cubic-bezier(.4,0,.2,1), opacity .5s ease, background .3s ease;
}
.ticket .id{
  font-family:var(--mono);
  font-size:10.5px;
  color:var(--celeste);
  margin-bottom:6px;
  display:flex; justify-content:space-between; align-items:center;
}
.ticket .prioridad{width:6px; height:6px; border-radius:50%;}
.prioridad.alta{background:#FF6B6B;}
.prioridad.media{background:#FFB74D;}
.prioridad.baja{background:#66D19E;}
.ticket .titulo-ticket{
  font-size:12.5px; color:#fff; font-weight:500; line-height:1.35;
  margin-bottom:8px;
}
.ticket .meta{
  display:flex; justify-content:space-between; align-items:center;
}
.ticket .avatar{
  width:20px; height:20px; border-radius:50%;
  background:var(--azul-electrico);
  display:flex; align-items:center; justify-content:center;
  font-family:var(--mono); font-size:9px; color:#fff; font-weight:600;
}
.ticket .tiempo{font-family:var(--mono); font-size:10px; color:rgba(227,234,245,0.45);}

@media (prefers-reduced-motion: reduce){
  .eyebrow .dot{animation:none;}
}

/* ============ SECTION SHELL ============ */
.section{padding:104px 44px;}
.section-head{max-width:640px; margin:0 auto 56px; text-align:center;}
.kicker{
  font-family:var(--mono);
  font-size:12.5px;
  color:var(--azul-electrico);
  text-transform:uppercase;
  letter-spacing:0.08em;
  margin-bottom:14px;
  display:block;
}
.section-head h2{
  font-family:var(--display);
  font-size:36px;
  font-weight:800;
  color:var(--marino);
  letter-spacing:-0.02em;
  margin-bottom:14px;
}
.section-head p{
  font-size:16px; color:var(--texto-suave); line-height:1.6;
}

/* ============ BENTO FEATURES ============ */
.bento{
  max-width:1180px; margin:0 auto;
  display:grid;
  grid-template-columns:repeat(4, 1fr);
  grid-template-rows:auto auto;
  gap:20px;
}
.bento-card{
  background:var(--niebla);
  border:1px solid var(--borde);
  border-radius:16px;
  padding:30px;
  transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}
.bento-card:hover{
  transform:translateY(-4px);
  box-shadow:0 20px 40px -20px rgba(11,42,91,0.18);
  border-color:rgba(30,136,229,0.3);
}
.bento-card.grande{grid-column:span 2; grid-row:span 2; background:linear-gradient(155deg, var(--marino), var(--azul-real));}
.bento-card.grande *{color:#fff;}
.bento-card.grande .icon-wrap{background:rgba(255,255,255,0.12);}
.bento-card:not(.grande){grid-column:span 2;}
@media(min-width:1000px){
  .bento-card:nth-child(2){grid-column:span 2;}
  .bento-card:nth-child(3){grid-column:span 1;}
  .bento-card:nth-child(4){grid-column:span 1;}
  .bento-card:nth-child(5){grid-column:span 2;}
}

.icon-wrap{
  width:44px; height:44px;
  border-radius:11px;
  background:rgba(30,136,229,0.1);
  display:flex; align-items:center; justify-content:center;
  margin-bottom:20px;
  color:var(--azul-electrico);
}
.bento-card h3{
  font-family:var(--display);
  font-size:19px; font-weight:700;
  margin-bottom:10px;
  color:var(--marino);
  letter-spacing:-0.01em;
}
.bento-card p{
  font-size:14.5px; color:var(--texto-suave); line-height:1.6;
}
.bento-card.grande p{color:rgba(227,234,245,0.85);}
.bento-card .mini-preview{
  margin-top:20px;
  background:rgba(255,255,255,0.08);
  border:1px solid rgba(255,255,255,0.14);
  border-radius:10px;
  padding:12px 14px;
  font-family:var(--mono);
  font-size:11.5px;
  color:rgba(227,234,245,0.75);
  display:flex; justify-content:space-between;
}

/* ============ PROCESO (numerado, secuencia real) ============ */
.proceso{
  background:var(--niebla);
  padding:104px 44px;
}
.pasos{
  max-width:1000px; margin:0 auto;
  display:grid; grid-template-columns:repeat(3,1fr); gap:0;
  position:relative;
}
.pasos::before{
  content:'';
  position:absolute; top:26px; left:14%; right:14%;
  height:1.5px;
  background:repeating-linear-gradient(90deg, var(--celeste) 0, var(--celeste) 6px, transparent 6px, transparent 12px);
}
.paso{position:relative; padding:0 24px; text-align:left;}
.paso .num{
  font-family:var(--display); font-weight:800; font-size:15px;
  width:52px; height:52px; border-radius:50%;
  background:var(--marino); color:#fff;
  display:flex; align-items:center; justify-content:center;
  margin-bottom:22px; position:relative; z-index:2;
  font-family:var(--mono);
}
.paso h3{
  font-family:var(--display); font-size:18px; font-weight:700;
  color:var(--marino); margin-bottom:10px;
}
.paso p{font-size:14.5px; color:var(--texto-suave); line-height:1.6;}

/* ============ SEGURIDAD ============ */
.seguridad{padding:104px 44px;}
.seg-grid{
  max-width:1100px; margin:0 auto;
  display:grid; grid-template-columns:repeat(4,1fr); gap:22px;
}
.seg-item{
  border:1px solid var(--borde);
  border-radius:14px;
  padding:26px 22px;
  transition:border-color .2s ease;
}
.seg-item:hover{border-color:var(--celeste);}
.seg-item .icon-wrap{background:rgba(11,42,91,0.06); color:var(--marino);}
.seg-item h4{font-family:var(--display); font-size:15.5px; font-weight:700; color:var(--marino); margin-bottom:8px;}
.seg-item p{font-size:13.3px; color:var(--texto-suave); line-height:1.55;}

/* ============ CTA FINAL ============ */
.cta-final{
  margin:0 44px 100px;
  background:linear-gradient(135deg, var(--marino), var(--azul-real) 60%, #123a82);
  border-radius:22px;
  padding:76px 60px;
  text-align:center;
  position:relative;
  overflow:hidden;
}
.cta-final::after{
  content:'';
  position:absolute; width:500px; height:500px; border-radius:50%;
  background:radial-gradient(circle, rgba(100,181,246,0.18), transparent 70%);
  bottom:-260px; left:50%; transform:translateX(-50%);
}
.cta-final h2{
  font-family:var(--display); font-size:34px; font-weight:800;
  color:#fff; margin-bottom:16px; position:relative; z-index:1;
  letter-spacing:-0.02em;
}
.cta-final p{
  color:rgba(227,234,245,0.82); font-size:16px; margin-bottom:32px;
  position:relative; z-index:1;
}
.cta-final .hero-ctas{justify-content:center; position:relative; z-index:1;}

/* ============ FOOTER ============ */
.footer{
  background:var(--marino);
  color:#fff;
  padding:20px 44px 28px;
}
.footer-links{
  display:flex; justify-content:center; gap:28px;
  padding-bottom:20px; margin-bottom:20px;
  border-bottom:1px solid rgba(255,255,255,0.1);
  font-size:13.5px;
}
.footer-links a{text-decoration:none; color:rgba(227,234,245,0.75);}
.footer-links a:hover{color:#fff;}
.footer-bottom{text-align:center; font-size:13px; color:rgba(227,234,245,0.55);}

/* ============ SCROLL REVEAL ============ */
.reveal{opacity:0; transform:translateY(24px); transition:opacity .7s ease, transform .7s ease;}
.reveal.visible{opacity:1; transform:translateY(0);}

/* ============ RESPONSIVE ============ */
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
  .nav a:not(.btn-login)::after{display:none;}
  .btn-login{margin-top:8px; text-align:center;}
  .hero{grid-template-columns:1fr; padding:56px 22px 80px; text-align:left;}
  .hero h1{font-size:36px;}
  .hero-meta{flex-wrap:wrap; gap:22px;}
  .section{padding:72px 22px;}
  .bento{grid-template-columns:1fr;}
  .bento-card.grande{grid-column:span 1; grid-row:span 1;}
  .bento-card:not(.grande){grid-column:span 1;}
  .pasos{grid-template-columns:1fr; gap:40px;}
  .pasos::before{display:none;}
  .seg-grid{grid-template-columns:1fr 1fr;}
  .cta-final{margin:0 20px 72px; padding:56px 26px;}
  .proceso, .seguridad{padding:72px 22px;}
}
@media(max-width:520px){
  .kanban-row-tickets{flex-direction:column;}
  .kanban-row-tickets .ticket{min-width:auto;}
  .seg-grid{grid-template-columns:1fr;}
}

:focus-visible{outline:2px solid var(--celeste); outline-offset:3px;}
</style>
</head>
<body>

<header class="header">
  <div class="logo">Mesa de <span>Ayuda</span></div>
  <nav class="nav" id="navMenu">
    <a href="/Mesa_Ayuda/">Inicio</a>
    <a href="/Mesa_Ayuda/noticias">Noticias</a>
    <a href="/Mesa_Ayuda/about">Sobre Nosotros</a>
    <a href="/Mesa_Ayuda/contacto">Contacto</a>
    <a href="/Mesa_Ayuda/login" class="btn-login">Iniciar Sesión</a>
  </nav>
  <button class="nav-toggle" id="navToggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="navMenu">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
</header>

<section class="hero">
  <div class="hero-copy">
    <div class="eyebrow"><span class="dot"></span> Mesa de ayuda para equipos internos</div>
    <h1>Cada solicitud, <em>bajo control</em>, de principio a fin.</h1>
    <p class="sub">Centraliza incidentes y solicitudes de tus colaboradores en un solo lugar. Asigna, da seguimiento y resuelve sin perder ni un ticket en el camino.</p>
    <div class="hero-ctas">
      <a href="/Mesa_Ayuda/login" class="btn-primario">Comenzar ahora</a>
      <a href="/Mesa_Ayuda/about" class="btn-secundario">Cómo funciona</a>
    </div>
    <div class="hero-meta">
      <div class="item">
        <div class="label">Autenticación</div>
        <div class="valor">Bloqueo tras 3 intentos</div>
      </div>
      <div class="item">
        <div class="label">Integridad</div>
        <div class="valor">Firma digital de datos</div>
      </div>
      <div class="item">
        <div class="label">Reportes</div>
        <div class="valor">Exportación a Excel</div>
      </div>
    </div>
  </div>

  <div class="kanban-wrap">
    <div class="kanban">
      <div class="kanban-head">
        <span class="titulo">tablero — hoy</span>
        <div class="puntos"><span></span><span></span><span></span></div>
      </div>
      <div class="kanban-cols">
        <div class="fila-nuevo">
          <div class="kanban-row-head"><span class="bola"></span>Nuevo</div>
          <div class="kanban-row-tickets">
            <div class="ticket">
              <div class="id">#TCK-3393 <span class="prioridad alta"></span></div>
              <div class="titulo-ticket">Correo institucional no sincroniza</div>
              <div class="meta"><span class="avatar">LT</span><span class="tiempo">ahora</span></div>
            </div>
            <div class="ticket">
              <div class="id">#TCK-3391 <span class="prioridad alta"></span></div>
              <div class="titulo-ticket">Impresora sin conexión — Piso 3</div>
              <div class="meta"><span class="avatar">JR</span><span class="tiempo">hace 4m</span></div>
            </div>
            <div class="ticket">
              <div class="id">#TCK-3390 <span class="prioridad media"></span></div>
              <div class="titulo-ticket">Acceso a carpeta compartida</div>
              <div class="meta"><span class="avatar">MP</span><span class="tiempo">hace 18m</span></div>
            </div>
          </div>
        </div>
        <div class="fila-progreso">
          <div class="kanban-row-head"><span class="bola"></span>En Progreso</div>
          <div class="kanban-row-tickets">
            <div class="ticket">
              <div class="id">#TCK-3387 <span class="prioridad media"></span></div>
              <div class="titulo-ticket">Restablecer contraseña VPN</div>
              <div class="meta"><span class="avatar">AC</span><span class="tiempo">hace 1h</span></div>
            </div>
          </div>
        </div>
        <div class="fila-resuelto">
          <div class="kanban-row-head"><span class="bola"></span>Resuelto</div>
          <div class="kanban-row-tickets">
            <div class="ticket">
              <div class="id">#TCK-3382 <span class="prioridad baja"></span></div>
              <div class="titulo-ticket">Actualización de antivirus</div>
              <div class="meta"><span class="avatar">JR</span><span class="tiempo">hace 3h</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <a href="#funciones" class="scroll-cue">
    <div class="flecha">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
    </div>
    Sigue bajando
  </a>
</section>

<section class="section" id="funciones">
  <div class="section-head reveal">
    <span class="kicker">Todo en un tablero</span>
    <h2>Diseñado para el ritmo real de soporte</h2>
    <p>No es una lista de funciones — es lo que tu equipo necesita para no perder tickets ni tiempo.</p>
  </div>

  <div class="bento">
    <div class="bento-card grande reveal">
      <div class="icon-wrap">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18"/><path d="M8 13h5"/><path d="M8 16.5h8"/></svg>
      </div>
      <h3>Reportes que se explican solos</h3>
      <p>Genera reportes de solicitudes e incidentes con un clic, listos para exportar a Excel y compartir con tu equipo o tu jefatura sin retocar nada.</p>
      <div class="mini-preview">
        <span>reporte_tickets_julio.xlsx</span>
        <span>247 registros</span>
      </div>
    </div>

    <div class="bento-card reveal">
      <div class="icon-wrap">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
      </div>
      <h3>Seguimiento en tiempo real</h3>
      <p>Cada ticket muestra su estado, responsable y tiempo transcurrido — nadie tiene que preguntar "¿cómo va esto?".</p>
    </div>

    <div class="bento-card reveal">
      <div class="icon-wrap">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/></svg>
      </div>
      <h3>Roles bien definidos</h3>
      <p>Administradores, agentes y colaboradores ven solo lo que les corresponde. Sin fricción, sin errores de permisos.</p>
    </div>

    <div class="bento-card reveal">
      <div class="icon-wrap">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
      </div>
      <h3>Historial completo</h3>
      <p>Cada interacción queda registrada, para auditorías internas o simplemente para no repetir la misma pregunta dos veces.</p>
    </div>
  </div>
</section>

<section class="proceso">
  <div class="section-head reveal">
    <span class="kicker">Ciclo de vida de un ticket</span>
    <h2>De la solicitud a la resolución, en tres pasos</h2>
  </div>
  <div class="pasos">
    <div class="paso reveal">
      <div class="num">01</div>
      <h3>El colaborador reporta</h3>
      <p>Describe el incidente o solicitud desde su panel — sin llamadas, sin correos perdidos en la bandeja de entrada.</p>
    </div>
    <div class="paso reveal">
      <div class="num">02</div>
      <h3>El agente asigna y atiende</h3>
      <p>El ticket entra al tablero, se prioriza y se asigna al agente disponible según el tipo de incidencia.</p>
    </div>
    <div class="paso reveal">
      <div class="num">03</div>
      <h3>Se resuelve y queda registrado</h3>
      <p>El colaborador ve el cierre del caso y el historial queda disponible para reportes futuros.</p>
    </div>
  </div>
</section>

<section class="seguridad">
  <div class="section-head reveal">
    <span class="kicker">Seguridad, en serio</span>
    <h2>Protegido desde el primer inicio de sesión</h2>
  </div>
  <div class="seg-grid">
    <div class="seg-item reveal">
      <div class="icon-wrap">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
      </div>
      <h4>Bloqueo de cuentas</h4>
      <p>Tras 3 intentos fallidos, la cuenta se bloquea automáticamente para prevenir accesos indebidos.</p>
    </div>
    <div class="seg-item reveal">
      <div class="icon-wrap">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9.5 12l1.8 1.8L15 10"/></svg>
      </div>
      <h4>Tokens CSRF</h4>
      <p>Cada formulario está protegido contra falsificación de solicitudes entre sitios.</p>
    </div>
    <div class="seg-item reveal">
      <div class="icon-wrap">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7.5" r="3.5"/><path d="M9.5 10.5L6 20M14.5 10.5L18 20"/></svg>
      </div>
      <h4>Firmas digitales</h4>
      <p>Los datos sensibles quedan firmados digitalmente para garantizar que nadie los alteró.</p>
    </div>
    <div class="seg-item reveal">
      <div class="icon-wrap">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18"/><circle cx="8" cy="14.5" r="1"/><circle cx="12" cy="14.5" r="1"/></svg>
      </div>
      <h4>Contraseñas robustas</h4>
      <p>Requisito de 8 a 12 caracteres, aplicado desde el registro de cada cuenta.</p>
    </div>
  </div>
</section>

<div class="cta-final reveal">
  <h2>Tu equipo ya está recibiendo solicitudes.<br>Dales un lugar donde organizarlas.</h2>
  <p>Crea tu cuenta y ten tu primer ticket gestionado en minutos.</p>
  <div class="hero-ctas">
    <a href="/Mesa_Ayuda/login" class="btn-primario">Comenzar ahora</a>
    <a href="/Mesa_Ayuda/contacto" class="btn-secundario">Hablar con soporte</a>
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
// Scroll reveal
const reveals = document.querySelectorAll('.reveal');
const io = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      io.unobserve(e.target);
    }
  });
}, { threshold: 0.15 });
reveals.forEach(el => io.observe(el));

// Header un poco más compacto al hacer scroll
const header = document.querySelector('.header');
window.addEventListener('scroll', () => {
  header.style.padding = window.scrollY > 40 ? '12px 44px' : '18px 44px';
});

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