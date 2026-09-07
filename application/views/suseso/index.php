<?php
/* /suseso/index.php - resultados + galería SUSESO */
$pdf_url   = base_url('uploads/suseso/Resultados_SUSESOS_2026.pdf');
$imagenes  = [
    base_url('uploads/suseso/suseso_1.jpeg'),
    base_url('uploads/suseso/suseso_2.jpeg'),
    base_url('uploads/suseso/suseso_3.jpeg'),
    base_url('uploads/suseso/suseso_4.jpeg'),
    base_url('uploads/suseso/suseso_5.jpeg'),
    base_url('uploads/suseso/suseso_6.jpeg'),
    base_url('uploads/suseso/suseso_7.jpeg'),
    base_url('uploads/suseso/suseso_8.jpeg'),
    base_url('uploads/suseso/suseso_9.jpeg'),
    base_url('uploads/suseso/suseso_10.jpeg'),
    base_url('uploads/suseso/suseso_11.jpeg'),
    base_url('uploads/suseso/suseso_12.jpeg'),
];
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SUSESO – Riesgos Psicosociales 2026</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500&display=swap');

:root{
    --bg:#eef3f9;
    --card:#ffffff;
    --text:#12304f;
    --muted:#6f8093;
    --primary:#1d4f91;
    --primary-700:#153d72;
    --accent:#e8a020;
    --line:#dbe5f0;
    --shadow:0 14px 40px rgba(18,48,79,.10);
    --radius:20px;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{
    font-family:'Inter',system-ui,sans-serif;
    background:
        radial-gradient(circle at top left,  rgba(29,79,145,.10), transparent 30%),
        radial-gradient(circle at top right, rgba(29,79,145,.08), transparent 28%),
        var(--bg);
    color:var(--text);
}

/* ── layout ─────────────────────────────────────── */
.wrap{
    max-width:1340px;
    margin:0 auto;
    padding:26px 20px 52px;
}

/* breadcrumb */
.breadcrumb{
    display:flex;align-items:center;gap:8px;
    color:var(--muted);font-size:14px;margin-bottom:20px;
}
.breadcrumb strong{color:var(--text)}

/* ── hero ───────────────────────────────────────── */
.hero{
    background:linear-gradient(135deg,#1d4f91 0%,#2b6cbe 100%);
    color:#fff;
    border-radius:28px;
    padding:32px 32px 26px;
    box-shadow:var(--shadow);
    margin-bottom:28px;
    position:relative;
    overflow:hidden;
}
.hero::after{
    content:'';
    position:absolute;inset:0;
    background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='28' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat;
    pointer-events:none;
}
.hero-inner{position:relative;z-index:1;display:flex;justify-content:space-between;align-items:flex-start;gap:20px;flex-wrap:wrap}
.hero h1{
    font-family:'Sora',sans-serif;
    font-size:clamp(24px,3.5vw,38px);
    font-weight:800;
    line-height:1.08;
    margin-bottom:10px;
}
.hero p{
    color:rgba(255,255,255,.85);
    font-size:15px;
    max-width:680px;
    line-height:1.55;
}
.hero-badges{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}
.badge{
    background:rgba(255,255,255,.14);
    border:1px solid rgba(255,255,255,.22);
    padding:8px 16px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
    white-space:nowrap;
    display:flex;align-items:center;gap:6px;
}
.badge-accent{background:var(--accent);border-color:transparent;color:#fff}

/* ── two-column layout ──────────────────────────── */
.content-grid{
    display:grid;
    grid-template-columns:1fr 420px;
    gap:24px;
    align-items:start;
}

/* ── PDF panel ──────────────────────────────────── */
.pdf-panel{
    background:var(--card);
    border-radius:26px;
    box-shadow:var(--shadow);
    border:1px solid rgba(255,255,255,.7);
    overflow:hidden;
}
.panel-header{
    padding:18px 22px 16px;
    border-bottom:1px solid var(--line);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
}
.panel-header-left{display:flex;align-items:center;gap:12px}
.panel-icon{
    width:40px;height:40px;
    background:linear-gradient(135deg,#1d4f91,#2b6cbe);
    border-radius:12px;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
}
.panel-icon svg{width:20px;height:20px;fill:#fff}
.panel-title{
    font-family:'Sora',sans-serif;
    font-size:16px;
    font-weight:700;
    color:var(--text);
    line-height:1.2;
}
.panel-sub{font-size:12px;color:var(--muted);margin-top:2px}
.btn-download{
    display:inline-flex;align-items:center;gap:6px;
    background:var(--primary);color:#fff;
    border:none;border-radius:10px;
    padding:9px 16px;font-size:13px;font-weight:600;
    cursor:pointer;text-decoration:none;
    transition:background .15s;
    white-space:nowrap;
}
.btn-download:hover{background:var(--primary-700)}
.btn-download svg{width:14px;height:14px;fill:#fff}

.pdf-embed{
    display:block;
    width:100%;
    height:680px;
    border:none;
    background:#f1f6fb;
}

.pdf-fallback{
    display:none;
    padding:40px 24px;
    text-align:center;
    color:var(--muted);
    font-size:14px;
    line-height:1.6;
}
.pdf-fallback svg{width:48px;height:48px;fill:var(--muted);margin-bottom:12px}

/* ── gallery panel ──────────────────────────────── */
.gallery-panel{
    display:flex;
    flex-direction:column;
    gap:18px;
}

/* mini stats row */
.stats-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}
.stat-card{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:18px;
    padding:16px 18px;
    box-shadow:0 6px 18px rgba(18,48,79,.06);
}
.stat-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:4px}
.stat-value{font-family:'Sora',sans-serif;font-size:22px;font-weight:800;color:var(--primary)}
.stat-note{font-size:12px;color:var(--muted);margin-top:2px}

/* slider */
.slider-card{
    background:var(--card);
    border-radius:26px;
    box-shadow:var(--shadow);
    overflow:hidden;
    border:1px solid rgba(255,255,255,.7);
}

.slider{
    position:relative;
    aspect-ratio:4/3;
    background:#dfe8f2;
    overflow:hidden;
}
.slide{
    position:absolute;inset:0;
    opacity:0;transform:scale(1.02);
    transition:opacity .65s ease,transform .65s ease;
    pointer-events:none;
}
.slide.active{opacity:1;transform:scale(1);pointer-events:auto}
.slide img{
    width:100%;height:100%;
    object-fit:contain;
    background:#edf3f9;
    display:block;cursor:pointer;
}

.slider-overlay{
    position:absolute;left:14px;bottom:14px;
    display:flex;align-items:center;gap:8px;
    background:rgba(8,24,42,.6);
    backdrop-filter:blur(10px);
    color:#fff;padding:8px 13px;border-radius:12px;z-index:3;
}
.slider-overlay strong{font-size:13px}
.slider-overlay span{color:rgba(255,255,255,.8);font-size:12px}

.nav-btn{
    position:absolute;top:50%;transform:translateY(-50%);z-index:3;
    width:40px;height:40px;border:none;border-radius:50%;cursor:pointer;
    background:rgba(255,255,255,.92);color:var(--primary);
    box-shadow:0 6px 18px rgba(0,0,0,.12);
    font-size:22px;font-weight:700;
    transition:transform .15s ease,background .15s ease;
}
.nav-btn:hover{background:#fff;transform:translateY(-50%) scale(1.05)}
.nav-prev{left:12px}.nav-next{right:12px}

.slider-footer{padding:14px 14px 16px;background:linear-gradient(to bottom,#fff,#f8fbfe)}

.thumbs{
    display:grid;
    grid-template-columns:repeat(6,minmax(0,1fr));
    gap:8px;
}
.thumb{
    border:2px solid transparent;border-radius:12px;
    overflow:hidden;cursor:pointer;background:#f1f6fb;
    transition:all .18s ease;
    box-shadow:0 3px 10px rgba(18,48,79,.06);
}
.thumb:hover{transform:translateY(-2px)}
.thumb.active{border-color:var(--primary);box-shadow:0 6px 16px rgba(29,79,145,.18)}
.thumb img{width:100%;aspect-ratio:4/3;object-fit:cover;display:block}

.dots{display:flex;gap:6px;justify-content:center;margin-top:12px}
.dot{
    width:8px;height:8px;border-radius:999px;
    background:#c4d3e5;cursor:pointer;
    transition:all .2s ease;
}
.dot.active{width:22px;background:var(--primary)}

/* info cards */
.info-grid{
    display:grid;grid-template-columns:1fr 1fr 1fr;
    gap:14px;
}
.info-card{
    background:rgba(255,255,255,.88);
    border:1px solid var(--line);border-radius:18px;
    padding:16px;
    box-shadow:0 6px 20px rgba(18,48,79,.05);
}
.info-card h3{font-family:'Sora',sans-serif;font-size:14px;color:var(--primary);margin-bottom:6px}
.info-card p{color:var(--muted);line-height:1.5;font-size:13px}

/* modal */
.modal{
    position:fixed;inset:0;
    background:rgba(8,16,28,.84);
    display:none;align-items:center;justify-content:center;
    padding:24px;z-index:9999;
}
.modal.open{display:flex}
.modal-box{position:relative;max-width:min(1100px,96vw);max-height:92vh}
.modal-box img{
    max-width:100%;max-height:92vh;
    border-radius:16px;display:block;
    box-shadow:0 24px 64px rgba(0,0,0,.38);background:#fff;
}
.modal-close{
    position:absolute;top:-13px;right:-13px;
    width:40px;height:40px;border:none;border-radius:50%;
    background:#fff;color:#12304f;font-size:22px;cursor:pointer;
    box-shadow:0 8px 22px rgba(0,0,0,.2);
}

/* ── responsive ─────────────────────────────────── */
@media(max-width:1100px){
    .content-grid{grid-template-columns:1fr}
    .pdf-embed{height:560px}
    .info-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:700px){
    .wrap{padding:14px 12px 32px}
    .hero{padding:22px 18px}
    .thumbs{grid-template-columns:repeat(4,1fr)}
    .stats-row{grid-template-columns:1fr 1fr}
    .info-grid{grid-template-columns:1fr}
    .pdf-embed{height:420px}
}
@media(max-width:480px){
    .thumbs{grid-template-columns:repeat(3,1fr)}
    .slider{aspect-ratio:3/4}
}
</style>
</head>
<body>
<div class="wrap">

    <div class="breadcrumb">
        <span>Inicio</span>
        <span>›</span>
        <span>SUSESO</span>
        <span>›</span>
        <strong>Riesgos Psicosociales 2026</strong>
    </div>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-inner">
            <div>
                <h1>Resultados CEAL-SM / SUSESO<br>Riesgos Psicosociales 2026</h1>
                <p>Empresa Portuaria Arica · Aplicación 23–27 de abril de 2026 · 23 de 24 trabajadores participaron</p>
                <div class="hero-badges">
                    <span class="badge">
                        <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:rgba(255,255,255,.85)"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm1 15h-2v-6h2zm0-8h-2V7h2z"/></svg>
                        Resultado General: Riesgo Bajo
                    </span>
                    <span class="badge badge-accent">
                        <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:#fff"><path d="M9 11l3 3L22 4"/></svg>
                        95% participación
                    </span>
                    <span class="badge">📋 <?php echo count($imagenes); ?> imágenes informativas</span>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN GRID: PDF izquierda + Galería derecha -->
    <div class="content-grid">

        <!-- ── PDF ── -->
        <div class="pdf-panel">
            <div class="panel-header">
                <div class="panel-header-left">
                    <div class="panel-icon">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM8 13h8v1.5H8V13zm0 3h5v1.5H8V16zm0-6h3v1.5H8V10z"/></svg>
                    </div>
                    <div>
                        <div class="panel-title">Informe de Resultados</div>
                        <div class="panel-sub">Resultados_SUSESOS_2026.pdf</div>
                    </div>
                </div>
                <a class="btn-download" href="<?php echo $pdf_url; ?>" download>
                    <svg viewBox="0 0 24 24"><path d="M12 16l-4-4h3V4h2v8h3l-4 4zm-7 4v-2h14v2H5z"/></svg>
                    Descargar PDF
                </a>
            </div>

            <iframe
                class="pdf-embed"
                src="<?php echo $pdf_url; ?>#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
                title="Resultados CEAL-SM SUSESO 2026"
                loading="lazy"
            ></iframe>

            <div class="pdf-fallback" id="pdfFallback">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5z"/></svg>
                <p>Tu navegador no puede mostrar el PDF en línea.<br>
                <a href="<?php echo $pdf_url; ?>" style="color:var(--primary);font-weight:600">Haz clic aquí para descargarlo</a>.</p>
            </div>
        </div>

        <!-- ── GALERÍA ── -->
        <div class="gallery-panel">

            <!-- stats rápidas -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-label">Participación</div>
                    <div class="stat-value">95%</div>
                    <div class="stat-note">23 de 24 trabajadores</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Nivel de riesgo</div>
                    <div class="stat-value" style="color:#1a9e4a">Bajo</div>
                    <div class="stat-note">Resultado general EPA</div>
                </div>
            </div>

            <!-- slider -->
            <section class="slider-card">
                <div class="slider" id="slider">
                    <?php foreach($imagenes as $i => $img): ?>
                        <div class="slide <?php echo $i===0?'active':''; ?>" data-index="<?php echo $i; ?>">
                            <img src="<?php echo $img; ?>"
                                 alt="Imagen SUSESO <?php echo $i+1; ?>"
                                 loading="<?php echo $i===0?'eager':'lazy'; ?>">
                        </div>
                    <?php endforeach; ?>

                    <button class="nav-btn nav-prev" id="prevBtn" aria-label="Anterior">‹</button>
                    <button class="nav-btn nav-next" id="nextBtn" aria-label="Siguiente">›</button>

                    <div class="slider-overlay">
                        <strong id="captionTitle">Imagen 1</strong>
                        <span id="captionText">Vista 1 de <?php echo count($imagenes); ?></span>
                    </div>
                </div>

                <div class="slider-footer">
                    <div class="thumbs" id="thumbs">
                        <?php foreach($imagenes as $i => $img): ?>
                            <div class="thumb <?php echo $i===0?'active':''; ?>" data-index="<?php echo $i; ?>">
                                <img src="<?php echo $img; ?>" alt="Miniatura <?php echo $i+1; ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="dots" id="dots">
                        <?php foreach($imagenes as $i => $img): ?>
                            <span class="dot <?php echo $i===0?'active':''; ?>" data-index="<?php echo $i; ?>"></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- info cards -->
            <div class="info-grid">
                <div class="info-card">
                    <h3>Navegación simple</h3>
                    <p>Usa las flechas, miniaturas o puntos para navegar entre imágenes.</p>
                </div>
                <div class="info-card">
                    <h3>Auto-avance</h3>
                    <p>La galería avanza sola cada 4,5 s. Se puede controlar manualmente.</p>
                </div>
                <div class="info-card">
                    <h3>Vista ampliada</h3>
                    <p>Haz clic sobre una imagen para verla en tamaño completo.</p>
                </div>
            </div>

        </div><!-- /gallery-panel -->
    </div><!-- /content-grid -->

</div><!-- /wrap -->

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-box">
        <button class="modal-close" id="modalClose" aria-label="Cerrar">×</button>
        <img id="modalImg" src="" alt="Imagen ampliada">
    </div>
</div>

<script>
(function(){
    const slides   = document.querySelectorAll('.slide');
    const thumbs   = document.querySelectorAll('.thumb');
    const dots     = document.querySelectorAll('.dot');
    const prevBtn  = document.getElementById('prevBtn');
    const nextBtn  = document.getElementById('nextBtn');
    const capTitle = document.getElementById('captionTitle');
    const capText  = document.getElementById('captionText');
    const modal    = document.getElementById('modal');
    const modalImg = document.getElementById('modalImg');
    const modalClose = document.getElementById('modalClose');

    let current = 0;
    let timer   = null;
    const total = slides.length;

    function render(i){
        current = i;
        slides.forEach((s,j) => s.classList.toggle('active', j===current));
        thumbs.forEach((t,j) => t.classList.toggle('active', j===current));
        dots.forEach((d,j)   => d.classList.toggle('active', j===current));
        capTitle.textContent = 'Imagen ' + (current+1);
        capText.textContent  = 'Vista ' + (current+1) + ' de ' + total;
    }
    function next(){ render((current+1)%total) }
    function prev(){ render((current-1+total)%total) }
    function restartAuto(){ clearInterval(timer); timer=setInterval(next,4500) }

    prevBtn.addEventListener('click', ()=>{ prev(); restartAuto() });
    nextBtn.addEventListener('click', ()=>{ next(); restartAuto() });
    thumbs.forEach(t => t.addEventListener('click', function(){ render(+this.dataset.index); restartAuto() }));
    dots.forEach(d   => d.addEventListener('click', function(){ render(+this.dataset.index); restartAuto() }));

    slides.forEach(s => s.querySelector('img').addEventListener('click', function(){
        modalImg.src = this.src;
        modal.classList.add('open');
    }));

    modalClose.addEventListener('click', ()=>{ modal.classList.remove('open'); modalImg.src='' });
    modal.addEventListener('click', e=>{ if(e.target===modal){ modal.classList.remove('open'); modalImg.src='' } });
    document.addEventListener('keydown', e=>{
        if(e.key==='ArrowRight'){ next(); restartAuto() }
        else if(e.key==='ArrowLeft'){ prev(); restartAuto() }
        else if(e.key==='Escape'){ modal.classList.remove('open'); modalImg.src='' }
    });

    render(0);
    restartAuto();
})();
</script>
</body>
</html>