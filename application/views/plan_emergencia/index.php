<?php
/* ==========================================================================
   MÓDULO EXCLUSIVO: PLAN DE EMERGENCIA Y EVACUACIÓN
   EMPRESA PORTUARIA ARICA (EPA) — PUERTO ARICA
   ========================================================================== */
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Plan de Emergencia y Evacuación - Empresa Portuaria Arica</title>
  <style>
    /* Scope aislado para plan_emergencia */
    .epa-emergency-module {
      --epa-bg-main: #f0f4f8; /* Fondo claro azulado */
      --epa-card-bg: #ffffff;
      --epa-border: #e2e8f0;
      --epa-primary: #2764b8; /* Azul institucional */
      --epa-primary-hover: #1d4ed8;
      --epa-cyan: #0ea5e9;
      --epa-light-cyan: #e0f2fe;
      --epa-green: #10b981;
      --epa-whatsapp: #25D366;
      --epa-red: #ef4444;
      --epa-amber: #f59e0b;
      --epa-text-main: #1e293b;
      --epa-text-muted: #64748b;
      
      background-color: var(--epa-bg-main);
      color: var(--epa-text-main);
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
      min-height: calc(100vh - 80px);
      padding: 20px 0 60px 0;
      margin: -15px -15px 0 -15px;
      box-sizing: border-box;
    }

    .epa-emergency-module * {
      box-sizing: border-box;
    }

    /* Container con ancho máximo y padding responsivo */
    .epa-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 20px;
    }

    /* Header Institucional (Estilo Tarjeta Azul) */
    .epa-header {
      background-color: var(--epa-primary);
      background-image: radial-gradient(circle at 80% 50%, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 60%),
                        radial-gradient(circle at 20% 120%, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0) 50%);
      border-radius: 20px;
      padding: 24px 30px;
      box-shadow: 0 10px 30px rgba(39, 100, 184, 0.2);
      margin-bottom: 30px;
    }

    .epa-header-flex {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
    }

    .epa-brand-area {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .epa-logo-img {
      height: 60px;
      width: auto;
      object-fit: contain;
      background: #ffffff;
      padding: 8px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .epa-title-box h1 {
      margin: 0;
      font-size: 24px;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: 0.3px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .epa-title-box p {
      margin: 6px 0 0 0;
      font-size: 14px;
      color: #e2e8f0;
      font-weight: 500;
    }

    .epa-header-actions {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .epa-btn-back {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: #ffffff !important;
      padding: 10px 20px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 14px;
      text-decoration: none !important;
      transition: all 0.25s ease;
      backdrop-filter: blur(4px);
    }

    .epa-btn-back:hover {
      background: #ffffff;
      color: var(--epa-primary) !important;
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    /* Badge de Alerta */
    .epa-alert-banner {
      background: #ffffff;
      color: var(--epa-amber);
      padding: 10px 18px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Navegación por pestañas */
    .epa-nav-tabs {
      display: flex;
      gap: 12px;
      margin-bottom: 30px;
      overflow-x: auto;
      padding-bottom: 8px;
      scrollbar-width: thin;
    }

    .epa-tab-btn {
      background: var(--epa-card-bg);
      border: 1px solid transparent;
      color: var(--epa-text-muted);
      padding: 14px 24px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
      white-space: nowrap;
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .epa-tab-btn:hover {
      background: var(--epa-light-cyan);
      color: var(--epa-primary);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.06);
    }

    .epa-tab-btn.active {
      background: var(--epa-primary);
      color: #ffffff;
      border-color: var(--epa-primary);
      box-shadow: 0 8px 20px rgba(39, 100, 184, 0.25);
    }

    /* Grid layout responsivo */
    .epa-cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
      gap: 26px;
    }

    /* Card Component */
    .epa-card {
      background: var(--epa-card-bg);
      border: none;
      border-radius: 20px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }

    .epa-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 32px rgba(0,0,0,0.1);
    }

    .epa-card-img-wrap {
      position: relative;
      width: 100%;
      min-height: 260px;
      background: #f8fafc; /* Fondo súper suave */
      border-bottom: 1px solid var(--epa-border);
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .epa-card-img-wrap img {
      width: 100%;
      height: 100%;
      max-height: 350px;
      object-fit: contain;
      object-position: center top;
      transition: transform 0.4s ease;
      filter: drop-shadow(0 6px 12px rgba(0,0,0,0.15));
    }

    .epa-card:hover .epa-card-img-wrap img {
      transform: scale(1.03);
    }

    .epa-card-badge {
      position: absolute;
      top: 16px;
      left: 16px;
      background: #ffffff;
      border: 1px solid var(--epa-border);
      color: var(--epa-primary);
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .epa-card-body {
      padding: 24px;
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .epa-card-title {
      font-size: 18px;
      font-weight: 800;
      color: var(--epa-text-main);
      margin: 0 0 10px 0;
      line-height: 1.3;
    }

    .epa-card-sector {
      font-size: 13px;
      color: var(--epa-cyan);
      font-weight: 700;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .epa-card-desc {
      font-size: 14px;
      color: var(--epa-text-muted);
      line-height: 1.5;
      margin: 0 0 20px 0;
      flex: 1;
    }

    .epa-card-footer {
      margin-top: auto;
      padding-top: 18px;
      border-top: 1px solid var(--epa-border);
      display: flex;
      gap: 12px;
    }

    /* Botones de acción */
    .epa-btn-primary {
      flex: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: var(--epa-primary);
      color: #ffffff !important;
      border: none;
      padding: 12px 18px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none !important;
      transition: all 0.2s ease;
      box-shadow: 0 4px 12px rgba(39, 100, 184, 0.25);
    }

    .epa-btn-primary:hover {
      background: var(--epa-primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(39, 100, 184, 0.35);
    }

    .epa-btn-call {
      flex: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: #f0f9ff;
      color: var(--epa-cyan) !important;
      border: 1px solid #bae6fd;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      text-decoration: none !important;
      transition: all 0.2s ease;
    }

    .epa-btn-call:hover {
      background: var(--epa-cyan);
      color: #ffffff !important;
      border-color: var(--epa-cyan);
      box-shadow: 0 4px 14px rgba(14, 165, 233, 0.3);
    }

    .epa-btn-whatsapp {
      flex: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: #f0fdf4;
      color: var(--epa-whatsapp) !important;
      border: 1px solid #bbf7d0;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      text-decoration: none !important;
      transition: all 0.2s ease;
    }

    .epa-btn-whatsapp:hover {
      background: var(--epa-whatsapp);
      color: #ffffff !important;
      border-color: var(--epa-whatsapp);
      box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
    }

    /* Tarjetas de Coordinadores */
    .epa-coord-avatar-wrap {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: var(--epa-light-cyan);
      border: 4px solid #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .epa-coord-avatar-wrap i {
      font-size: 38px;
      color: var(--epa-primary);
    }

    .epa-coord-phone {
      display: flex;
      align-items: center;
      gap: 8px;
      background: #f8fafc;
      border: 1px solid var(--epa-border);
      padding: 10px 16px;
      border-radius: 10px;
      font-family: monospace;
      font-size: 14px;
      font-weight: 700;
      color: var(--epa-text-main);
      margin: 10px 0 20px 0;
    }

    /* Modal Lightbox */
    .epa-modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.85);
      z-index: 99999;
      align-items: center;
      justify-content: center;
      padding: 20px;
      backdrop-filter: blur(8px);
    }

    .epa-modal-overlay.active {
      display: flex;
    }

    .epa-modal-box {
      position: relative;
      background: var(--epa-card-bg);
      border-radius: 20px;
      max-width: 95vw;
      max-height: 95vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .epa-modal-header {
      padding: 18px 24px;
      background: #ffffff;
      border-bottom: 1px solid var(--epa-border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .epa-modal-title {
      font-size: 18px;
      font-weight: 800;
      color: var(--epa-text-main);
      margin: 0;
    }

    .epa-modal-close {
      background: #fee2e2;
      border: none;
      color: var(--epa-red);
      width: 38px;
      height: 38px;
      border-radius: 10px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      transition: all 0.2s ease;
    }

    .epa-modal-close:hover {
      background: var(--epa-red);
      color: #ffffff;
      transform: scale(1.05);
    }

    .epa-modal-body {
      padding: 20px;
      overflow: auto;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f1f5f9;
      max-height: calc(95vh - 75px);
    }

    .epa-modal-body img {
      max-width: 100%;
      max-height: 85vh;
      object-fit: contain;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    /* Secciones ocultas / visibles */
    .epa-tab-content {
      display: none;
    }

    .epa-tab-content.active {
      display: block;
      animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Banner de Módulo en Construcción */
    .epa-wip-banner {
      background: linear-gradient(135deg, #78350f 0%, #92400e 40%, #b45309 100%);
      border: 2px solid #f59e0b;
      border-radius: 16px;
      padding: 18px 24px;
      margin-bottom: 28px;
      display: flex;
      align-items: center;
      gap: 18px;
      box-shadow: 0 8px 24px rgba(245, 158, 11, 0.25);
      animation: fadeIn 0.5s ease;
    }

    .epa-wip-icon {
      font-size: 36px;
      flex-shrink: 0;
    }

    @keyframes wip-pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50%       { transform: scale(1.12); opacity: 0.8; }
    }

    .epa-wip-text h3 {
      margin: 0 0 4px 0;
      font-size: 17px;
      font-weight: 800;
      color: #fef3c7;
      letter-spacing: 0.3px;
    }

    .epa-wip-text p {
      margin: 0;
      font-size: 13px;
      color: #fde68a;
      line-height: 1.4;
    }

    .epa-wip-badge {
      margin-left: auto;
      background: #f59e0b;
      color: #1c0a00;
      font-size: 11px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      padding: 6px 14px;
      border-radius: 20px;
      white-space: nowrap;
      flex-shrink: 0;
    }

    /* Responsividad ajustada */
    @media (max-width: 768px) {
      .epa-cards-grid {
        grid-template-columns: 1fr;
      }
      .epa-header {
        border-radius: 12px;
        padding: 20px;
      }
      .epa-header-flex {
        flex-direction: column;
        align-items: flex-start;
      }
      .epa-header-actions {
        width: 100%;
        justify-content: space-between;
      }
      .epa-logo-img {
        height: 48px;
      }
      .epa-wip-banner {
        flex-wrap: wrap;
      }
      .epa-wip-badge {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<?php
  $base_img = base_url('imagenes/plan_emergencia/');
  
  // Normalizador de teléfono para WhatsApp
  function formatWhatsAppUrl($phone_raw) {
    $clean = preg_replace('/[^0-9]/', '', $phone_raw);
    if (empty($clean)) return '#';
    // Si empieza por 9 y tiene 9 dígitos (ej. 995455354), agregar 56
    if (strlen($clean) == 9 && substr($clean, 0, 1) == '9') {
      $clean = '56' . $clean;
    }
    return 'https://wa.me/' . $clean;
  }
?>

<div class="epa-emergency-module">

  <!-- ===== HEADER INSTITUCIONAL ===== -->
  <header class="epa-header">
    <div class="epa-container">
      <div class="epa-header-flex">
        
        <div class="epa-brand-area">
          <img src="<?= $base_img ?>logo_puertoarica.png" alt="Empresa Portuaria Arica" class="epa-logo-img" onerror="this.style.display='none'">
          <div class="epa-title-box">
            <h1><i class="fa fa-shield" style="color: var(--epa-cyan);"></i> PLAN DE EMERGENCIA Y EVACUACIÓN</h1>
            <p>Empresa Portuaria Arica &bull; Prevención de Riesgos y Seguridad Operativa</p>
          </div>
        </div>

        <div class="epa-header-actions">
          <div class="epa-alert-banner">
            <i class="fa fa-exclamation-triangle"></i>
            <span>ESTADO: OPERATIVO / PUERTA SUR EN MANTENIMIENTO</span>
          </div>
          <a href="<?= site_url('inicio'); ?>" class="epa-btn-back">
            <i class="fa fa-arrow-left"></i> Volver al Inicio
          </a>
        </div>

      </div>
    </div>
  </header>

  <div class="epa-container">

    <!-- ===== BANNER: MÓDULO EN CONSTRUCCIÓN ===== -->
    <div class="epa-wip-banner" role="alert">
      <span class="epa-wip-icon">🚧</span>
      <div class="epa-wip-text">
        <h3>Módulo en Construcción</h3>
        <p>Estamos trabajando en este módulo para mejorar la experiencia. El contenido que visualiza es de referencia y podría actualizarse pronto.</p>
      </div>
      <span class="epa-wip-badge">En Desarrollo</span>
    </div>

    <!-- ===== NAVEGACIÓN DE PESTAÑAS ===== -->
    <nav class="epa-nav-tabs">
      <button class="epa-tab-btn active" onclick="switchEpaTab('planos', this)">
        <i class="fa fa-map"></i> Planos de Evacuación
      </button>
      <button class="epa-tab-btn" onclick="switchEpaTab('vias', this)">
        <i class="fa fa-sign-out"></i> Vías de Escape
      </button>
      <button class="epa-tab-btn" onclick="switchEpaTab('coordinadores', this)">
        <i class="fa fa-users"></i> Coordinadores de Emergencia
      </button>
      <button class="epa-tab-btn" onclick="switchEpaTab('protocolos', this)">
        <i class="fa fa-list-alt"></i> Protocolos de Actuación
      </button>
      <button class="epa-tab-btn" onclick="switchEpaTab('urgencias', this)">
        <i class="fa fa-phone-square"></i> Teléfonos de Urgencia
      </button>
    </nav>

    <!-- ===================================================================
         1. SECCIÓN: PLANOS DE EVACUACIÓN
         =================================================================== -->
    <section id="tab-planos" class="epa-tab-content active">
      <div class="epa-cards-grid">

        <!-- Plano 1: Plano 2° Piso -->
        <article class="epa-card">
          <div class="epa-card-img-wrap">
            <span class="epa-card-badge"><i class="fa fa-building"></i> Nivel 2</span>
            <img src="<?= $base_img ?>plano_2piso.png" alt="Plano Evacuación 2° Piso">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">Plano Evacuación 2° Piso</h2>
            <div class="epa-card-sector"><i class="fa fa-map-marker"></i> Sector: Edificio Administrativo - Nivel 2</div>
            <p class="epa-card-desc">Distribución de rutas de evacuación, extintores y salidas de emergencia del segundo piso de oficinas corporativas.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('Plano Evacuación 2° Piso', '<?= $base_img ?>plano_2piso.png')">
                <i class="fa fa-search-plus"></i> VER PLANO EN GRANDE
              </button>
            </div>
          </div>
        </article>

        <!-- Plano 2: Mapa General Puerto -->
        <article class="epa-card">
          <div class="epa-card-img-wrap">
            <span class="epa-card-badge"><i class="fa fa-anchor"></i> General</span>
            <img src="<?= $base_img ?>plano_general.jpg" alt="Mapa General del Recinto Portuario">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">Mapa General del Recinto Portuario</h2>
            <div class="epa-card-sector"><i class="fa fa-map-marker"></i> Sector: Terminal Portuario Arica (EPA)</div>
            <p class="epa-card-desc">Vista integral del puerto con delimitación de zonas operativas, puntos de encuentro y vías de evacuación rápida.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('Mapa General del Recinto Portuario', '<?= $base_img ?>plano_general.jpg')">
                <i class="fa fa-search-plus"></i> VER PLANO EN GRANDE
              </button>
            </div>
          </div>
        </article>
        
        <!-- Plano 3: Salidas de Emergencia (Anexo 3) -->
        <article class="epa-card">
          <div class="epa-card-img-wrap">
            <span class="epa-card-badge" style="border-color:var(--epa-amber); color:var(--epa-amber);"><i class="fa fa-sign-out"></i> Salidas</span>
            <img src="<?= $base_img ?>anexo3_salidas.jpg" alt="Salidas de Emergencia y Puntos de Reunión">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">Salidas de Emergencia</h2>
            <div class="epa-card-sector"><i class="fa fa-location-arrow"></i> Sector: Zonas exteriores e interiores</div>
            <p class="epa-card-desc">Información clara y visual de las rutas de evacuación, puertas de escape operativas y puntos de reunión establecidos.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('Salidas de Emergencia y Puntos de Reunión', '<?= $base_img ?>anexo3_salidas.jpg')">
                <i class="fa fa-search-plus"></i> VER PLANO EN GRANDE
              </button>
            </div>
          </div>
        </article>

      </div>
    </section>

    <!-- ===================================================================
         2. SECCIÓN: VÍAS DE ESCAPE
         =================================================================== -->
    <section id="tab-vias" class="epa-tab-content">
      <div class="epa-cards-grid">

        <!-- Vía 1: Vía de escape al punto de encuentro -->
        <article class="epa-card">
          <div class="epa-card-img-wrap">
            <span class="epa-card-badge" style="border-color:var(--epa-green); color:var(--epa-green);"><i class="fa fa-map-signs"></i> Ruta Urbana</span>
            <img src="<?= $base_img ?>anexo4_vias.jpg" alt="Vía de escape al punto de encuentro">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">Trayecto al Punto de Encuentro</h2>
            <div class="epa-card-sector"><i class="fa fa-location-arrow"></i> Sector: Máximo Lira hasta San Marcos</div>
            <p class="epa-card-desc">Fotografía aérea con el trayecto desde el frontis del edificio corporativo hasta el punto de encuentro en San Marcos con Sotomayor.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('Trayecto al Punto de Encuentro', '<?= $base_img ?>anexo4_vias.jpg')">
                <i class="fa fa-eye"></i> VER MAPA DE VÍAS
              </button>
            </div>
          </div>
        </article>

        <!-- Vía 2: Ruta Escaleras -->
        <article class="epa-card">
          <div class="epa-card-img-wrap">
            <span class="epa-card-badge" style="border-color:var(--epa-amber); color:var(--epa-amber);"><i class="fa fa-stairs"></i> Escaleras</span>
            <img src="<?= $base_img ?>ruta_escaleras.jpg" alt="Ruta Segura Escaleras">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">Ruta Segura - Escaleras</h2>
            <div class="epa-card-sector"><i class="fa fa-arrow-down"></i> Sector: Escalera junto estacionamiento</div>
            <p class="epa-card-desc">Secuencia de salida paso a paso por las escaleras de emergencia y demarcación hasta la zona de seguridad.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('Ruta Segura - Escaleras', '<?= $base_img ?>ruta_escaleras.jpg')">
                <i class="fa fa-eye"></i> VER MAPA DE VÍAS
              </button>
            </div>
          </div>
        </article>
        
        <!-- Vía 3: Rutas Letreros -->
        <article class="epa-card">
          <div class="epa-card-img-wrap">
            <span class="epa-card-badge" style="border-color:var(--epa-cyan); color:var(--epa-cyan);"><i class="fa fa-bolt"></i> Señalización</span>
            <img src="<?= $base_img ?>rutas_letreros.png" alt="Señalización Rutas">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">Señalización de Rutas</h2>
            <div class="epa-card-sector"><i class="fa fa-sign-in"></i> Sector: Interiores Edificio</div>
            <p class="epa-card-desc">Guía de señalética luminosa y rutas de evacuación correctamente señalizadas para una evacuación rápida, ordenada y segura.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('Señalización de Rutas', '<?= $base_img ?>rutas_letreros.png')">
                <i class="fa fa-eye"></i> VER MAPA DE VÍAS
              </button>
            </div>
          </div>
        </article>

      </div>
    </section>

    <!-- ===================================================================
         3. SECCIÓN: COORDINADORES DE EMERGENCIA
         =================================================================== -->
    <section id="tab-coordinadores" class="epa-tab-content">
      <div class="epa-cards-grid">

        <!-- Infografía Coordinadores -->
        <article class="epa-card">
          <div class="epa-card-img-wrap" style="min-height: 200px;">
            <span class="epa-card-badge"><i class="fa fa-info-circle"></i> Roles</span>
            <img src="<?= $base_img ?>coordinadores.png" alt="Roles de Coordinadores">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">Rol del Coordinador</h2>
            <div class="epa-card-sector"><i class="fa fa-check-circle"></i> Responsabilidad de Evacuación</div>
            <p class="epa-card-desc">Su objetivo es evaluar la situación, ejecutar y supervisar toda acción tendiente al control de la emergencia.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('Roles de Coordinadores', '<?= $base_img ?>coordinadores.png')">
                <i class="fa fa-search-plus"></i> VER INFOGRAFÍA
              </button>
            </div>
          </div>
        </article>

        <!-- Coordinador 1: Roxana Bavestrello -->
        <article class="epa-card">
          <div class="epa-card-body" style="align-items:center; text-align:center;">
            <div class="epa-coord-avatar-wrap">
              <i class="fa fa-female"></i>
            </div>
            <h2 class="epa-card-title" style="margin-bottom:4px;">Sra. Roxana Bavestrello M</h2>
            <div class="epa-card-sector" style="justify-content:center;">
              <i class="fa fa-star" style="color:var(--epa-amber);"></i> Coordinador de Emergencia
            </div>
            <div class="epa-coord-phone" style="flex-direction: column; align-items: center; font-size:13px; line-height: 1.6;">
              <div><i class="fa fa-phone" style="color:var(--epa-cyan);"></i> (+569) 9545 5354</div>
              <div><i class="fa fa-envelope" style="color:var(--epa-cyan);"></i> rbavestrello@puertoarica.cl</div>
            </div>
            <div class="epa-card-footer" style="width:100%;">
              <a href="tel:+56995455354" class="epa-btn-call">
                <i class="fa fa-phone"></i> Llamar
              </a>
              <a href="<?= formatWhatsAppUrl('+56995455354') ?>" target="_blank" rel="noopener noreferrer" class="epa-btn-whatsapp">
                <i class="fa fa-whatsapp"></i> WhatsApp
              </a>
            </div>
          </div>
        </article>

        <!-- Coordinador 2: Juan Barrios -->
        <article class="epa-card">
          <div class="epa-card-body" style="align-items:center; text-align:center;">
            <div class="epa-coord-avatar-wrap">
              <i class="fa fa-male"></i>
            </div>
            <h2 class="epa-card-title" style="margin-bottom:4px;">Sr. Juan Barrios M</h2>
            <div class="epa-card-sector" style="justify-content:center;">
              <i class="fa fa-shield" style="color:var(--epa-cyan);"></i> Coordinador de Emergencia
            </div>
            <div class="epa-coord-phone" style="flex-direction: column; align-items: center; font-size:13px; line-height: 1.6;">
              <div><i class="fa fa-phone" style="color:var(--epa-cyan);"></i> (+569) 6634 7008</div>
              <div><i class="fa fa-envelope" style="color:var(--epa-cyan);"></i> jbarrios@puertoarica.cl</div>
            </div>
            <div class="epa-card-footer" style="width:100%;">
              <a href="tel:+56966347008" class="epa-btn-call">
                <i class="fa fa-phone"></i> Llamar
              </a>
              <a href="<?= formatWhatsAppUrl('+56966347008') ?>" target="_blank" rel="noopener noreferrer" class="epa-btn-whatsapp">
                <i class="fa fa-whatsapp"></i> WhatsApp
              </a>
            </div>
          </div>
        </article>

      </div>
    </section>

    <!-- ===================================================================
         4. SECCIÓN: PROTOCOLOS DE ACTUACIÓN
         =================================================================== -->
    <section id="tab-protocolos" class="epa-tab-content">
      <div class="epa-cards-grid">

        <!-- Protocolo 1: Tsunami -->
        <article class="epa-card">
          <div class="epa-card-img-wrap">
            <span class="epa-card-badge" style="border-color:var(--epa-cyan); color:var(--epa-cyan);"><i class="fa fa-tint"></i> Marítimo</span>
            <img src="<?= $base_img ?>info_tsunami.png" alt="¿Qué hacer ante un Tsunami?">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">¿Qué hacer ante un Tsunami?</h2>
            <div class="epa-card-sector"><i class="fa fa-exclamation-triangle"></i> Instrucciones de Seguridad</div>
            <p class="epa-card-desc">Pasos a seguir al momento de recibir un llamado o alerta de tsunami, desde la evaluación inicial hasta el punto de encuentro.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('¿Qué hacer ante un Tsunami?', '<?= $base_img ?>info_tsunami.png')">
                <i class="fa fa-file-text-o"></i> VER INFOGRAFÍA
              </button>
            </div>
          </div>
        </article>

        <!-- Protocolo 2: Simulacro -->
        <article class="epa-card">
          <div class="epa-card-img-wrap">
            <span class="epa-card-badge" style="border-color:var(--epa-green); color:var(--epa-green);"><i class="fa fa-refresh"></i> Ejercicios</span>
            <img src="<?= $base_img ?>simulacro.png" alt="Evacuación - Simulacro">
          </div>
          <div class="epa-card-body">
            <h2 class="epa-card-title">Evacuación - Simulacro</h2>
            <div class="epa-card-sector"><i class="fa fa-camera"></i> Secuencia Fotográfica</div>
            <p class="epa-card-desc">Registro del ejercicio de simulacro: inicio de la evacuación, descenso por escaleras, desplazamiento y llegada a zona segura.</p>
            <div class="epa-card-footer">
              <button class="epa-btn-primary" onclick="openEpaModal('Evacuación - Simulacro', '<?= $base_img ?>simulacro.png')">
                <i class="fa fa-file-text-o"></i> VER SECUENCIA
              </button>
            </div>
          </div>
        </article>

      </div>
    </section>

    <!-- ===================================================================
         5. SECCIÓN: TELÉFONOS DE URGENCIA
         =================================================================== -->
    <section id="tab-urgencias" class="epa-tab-content">
      <div class="epa-cards-grid">

        <article class="epa-card">
          <div class="epa-card-body" style="align-items:center; text-align:center;">
            <i class="fa fa-ambulance" style="font-size:40px; color:#ef4444; margin-bottom:12px;"></i>
            <h2 class="epa-card-title">SAMU - Ambulancia</h2>
            <p class="epa-card-desc">Atención médica de urgencia y rescate</p>
            <a href="tel:131" class="epa-btn-call" style="width:100%; background:#ef4444; border-color:#fca5a5;">
              <i class="fa fa-phone"></i> LLAMAR A 131
            </a>
          </div>
        </article>

        <article class="epa-card">
          <div class="epa-card-body" style="align-items:center; text-align:center;">
            <i class="fa fa-fire-extinguisher" style="font-size:40px; color:#f97316; margin-bottom:12px;"></i>
            <h2 class="epa-card-title">Bomberos Arica</h2>
            <p class="epa-card-desc">Control de incendios y materiales peligrosos</p>
            <a href="tel:132" class="epa-btn-call" style="width:100%; background:#f97316; border-color:#fdba74;">
              <i class="fa fa-phone"></i> LLAMAR A 132
            </a>
          </div>
        </article>

        <article class="epa-card">
          <div class="epa-card-body" style="align-items:center; text-align:center;">
            <i class="fa fa-shield" style="font-size:40px; color:#3b82f6; margin-bottom:12px;"></i>
            <h2 class="epa-card-title">Carabineros de Chile</h2>
            <p class="epa-card-desc">Orden público y emergencias policiales</p>
            <a href="tel:133" class="epa-btn-call" style="width:100%; background:#3b82f6; border-color:#93c5fd;">
              <i class="fa fa-phone"></i> LLAMAR A 133
            </a>
          </div>
        </article>

        <article class="epa-card">
          <div class="epa-card-body" style="align-items:center; text-align:center;">
            <i class="fa fa-anchor" style="font-size:40px; color:var(--epa-cyan); margin-bottom:12px;"></i>
            <h2 class="epa-card-title">Capitanía de Puerto / Gobernación Marítima</h2>
            <p class="epa-card-desc">Emergencias marítimas y rescate costero</p>
            <a href="tel:137" class="epa-btn-call" style="width:100%; background:var(--epa-blue); border-color:var(--epa-light-cyan);">
              <i class="fa fa-phone"></i> LLAMAR A 137
            </a>
          </div>
        </article>

      </div>
    </section>

  </div><!-- /container -->

</div><!-- /epa-emergency-module -->

<!-- ===== MODAL LIGHTBOX PARA VER PLANOS ===== -->
<div id="epaModal" class="epa-modal-overlay" onclick="closeEpaModal()">
  <div class="epa-modal-box" onclick="event.stopPropagation()">
    <div class="epa-modal-header">
      <h3 id="epaModalTitle" class="epa-modal-title">Vista Ampliada</h3>
      <button class="epa-modal-close" onclick="closeEpaModal()"><i class="fa fa-times"></i></button>
    </div>
    <div class="epa-modal-body">
      <img id="epaModalImg" src="" alt="Plano ampliado">
    </div>
  </div>
</div>

<!-- ===== SCRIPT INTERACTIVO ===== -->
<script>
  function switchEpaTab(tabName, btnElement) {
    // Desactivar todos los botones
    var buttons = document.querySelectorAll('.epa-tab-btn');
    buttons.forEach(function(b) { b.classList.remove('active'); });

    // Ocultar todos los contenidos de pestaña
    var contents = document.querySelectorAll('.epa-tab-content');
    contents.forEach(function(c) { c.classList.remove('active'); });

    // Activar botón y contenido seleccionado
    if (btnElement) { btnElement.classList.add('active'); }
    var target = document.getElementById('tab-' + tabName);
    if (target) { target.classList.add('active'); }
  }

  function openEpaModal(title, imgSrc) {
    document.getElementById('epaModalTitle').textContent = title;
    document.getElementById('epaModalImg').src = imgSrc;
    document.getElementById('epaModal').classList.add('active');
  }

  function closeEpaModal() {
    document.getElementById('epaModal').classList.remove('active');
  }

  // Cerrar modal con tecla Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeEpaModal();
    }
  });
</script>

</body>
</html>
