        <?php /* /ciberconsejos/index.php - autónomo, sin includes */ ?>
        <!doctype html>
        <html lang="es">
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Ciber-Consejos</title>
        <style>
        :root{
            --bg:#f4f7fb;          /* fondo general */
            --card:#ffffff;        /* fondo tarjeta */
            --text:#0f1b3d;        /* azul oscuro para textos */
            --muted:#7a8599;       /* texto secundario */
            --primary:#233b84;     /* azul corporativo aprox */
            --primary-700:#1b2e69; /* hover */
            --shadow:0 6px 18px rgba(20,36,90,.08);
            --radius:14px;
        }
        *{box-sizing:border-box}
        body{
            margin:0; font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;
            background:var(--bg); color:var(--text);
        }
        .wrap{max-width:1200px; margin:24px auto; padding:0 16px}
        .page-title{
            display:flex; align-items:center; gap:10px; margin:6px 0 18px;
            font-weight:700; letter-spacing:.2px; color:var(--text);
        }
        .crumb{color:var(--muted); font-weight:600}
        .grid{
            display:grid; gap:20px;
            grid-template-columns:repeat(12,1fr);
        }
        /* 3 tarjetas por fila en desktop, 2 en tablet, 1 en móvil */
        .col{grid-column:span 12}
        @media (min-width:700px){ .col{grid-column:span 6} }
        @media (min-width:1024px){ .col{grid-column:span 4} }

        .card{
            background:var(--card); border-radius:var(--radius);
            box-shadow:var(--shadow); padding:18px 18px 16px; min-height:150px;
            display:flex; flex-direction:column; justify-content:space-between;
        }
        .title{
            margin:0 0 14px; font-size:20px; line-height:1.25;
            color:var(--primary); font-weight:800; text-transform:uppercase;
        }
        .meta{font-size:13px; color:var(--muted); margin:-4px 0 10px}
        .btn{
            display:inline-flex; align-items:center; gap:10px;
            background:var(--primary); color:#fff; padding:10px 14px;
            border-radius:10px; text-decoration:none; font-weight:700;
            width:max-content; transition:transform .06s ease, background .15s ease;
        }
        .btn:hover{ background:var(--primary-700); transform:translateY(-1px) }
        .pdficon{ width:18px; height:18px; display:block }
        .section{
            margin:8px 0 22px; color:var(--muted); font-weight:700
        }
        </style>
        </head>
        <body>
        <div class="wrap">
            <div class="page-title">
            <span class="crumb">Inicio</span>
            <span>›</span>
            <span>Ciber-Consejos</span>
            </div>

            <div class="grid">

            <!-- 1 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Datos fundamentales de la ANCI</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4681/Fundamentales_ANCI_PDF.pdf" rel="noopener">
                    <!-- pdf icon svg -->
                    <svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/>
                    <path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/>
                    <rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/>
                    <path d="M9 16h6" stroke="#fff" stroke-width="4"/>
                    <path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/>
                    </svg>
                    Descargar
                </a>
                </div>
            </div>

            <!-- 2 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | PIN a tu SIM</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4682/Ciberconsejos_2025_02_PIN_SIM.pdf" rel="noopener">
                    <svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>
                    Descargar
                </a>
                </div>
            </div>

            <!-- 3 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Cuidados con los códigos QR</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4687/Ciberconsejos_2025_02.pdf" rel="noopener">
                    <svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>
                    Descargar
                </a>
                </div>
            </div>

            <!-- 4 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Ciberacoso</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4691/Ciberconsejos_2025_Ciberacoso.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 5 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Cyberday 2025</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4711/Ciberconsejos_ANCI_2025_CyberDay_wTyr0Uq.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 6 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Tiendas falsas</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4715/Ciberconsejos_2025_Tiendas_falsas.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 7 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Filtración de claves e infostealers</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4718/Ciberconsejos_ANCI_2025_Filtraci%C3%B3n_de_claves.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 8 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Evita el malware</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4725/Ciberconsejos_2025_Malware_88PTvik.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 9 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Pasos básicos ante el robo de tu equipo</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4729/Ciberconsejos_ANCI_2025_-_Robo.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 10 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Gestores de claves</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4731/Ciberconsejos_ANCI_2025_Gestores.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 11 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | ¡Cuidado con los mensajes falsos!</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4742/Ciberconsejos_2025_Phishing.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 12 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Recupera tu WhatsApp</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4749/Ciberconsejos_ANCI_2025_Robo_de_Whatsapp.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            <!-- 13 -->
            <div class="col">
                <div class="card">
                <h3 class="title">Ciberconsejos | Privacidad al navegar</h3>
                <a class="btn" target="_blank" href="https://anci.gob.cl/documents/4763/Ciberconsejos_ANCI_2025_-_Privacidad.pdf" rel="noopener"><svg class="pdficon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" fill="#fff"/><path d="M14 2v6h6" stroke="#cfd6f0" stroke-width="2"/><rect x="6.5" y="12.5" width="11" height="7" rx="1.5" stroke="#e9edf7"/><path d="M9 16h6" stroke="#fff" stroke-width="4"/><path d="M9 16h6" stroke="#cbd5ff" stroke-width="2"/></svg>Descargar</a>
                </div>
            </div>

            </div><!-- grid -->
        </div><!-- wrap -->
        </body>
        </html>
