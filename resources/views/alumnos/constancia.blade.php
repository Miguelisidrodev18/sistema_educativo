<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Constancia de Matrícula — {{ strtoupper($alumno->apellidos . ', ' . $alumno->nombres) }}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: Arial, Helvetica, sans-serif;
    background: #e9eef6;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 30px 15px;
    min-height: 100vh;
}

/* ── TOOLBAR ── */
.toolbar {
    width: 800px;
    max-width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    background: white;
    border-radius: 12px;
    padding: 14px 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,.1);
}
.toolbar-title {
    font-size: 14px;
    font-weight: 700;
    color: #0b3d91;
    display: flex;
    align-items: center;
    gap: 8px;
}
.toolbar-actions { display: flex; gap: 8px; flex-wrap: wrap; }

.btn {
    padding: 9px 18px;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none;
    transition: all .2s;
}
.btn:hover { opacity: .88; transform: translateY(-1px); }
.btn:active { transform: translateY(0); }
.btn-print   { background: #0b3d91; color: white; }
.btn-back    { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
.btn-back:hover { background: #e2e8f0; }
.btn-wa      { background: #25D366; color: white; }
.btn-wa-dark { background: #128C7E; color: white; }
.btn-pdf     { background: #ef4444; color: white; }

/* ── MODAL WHATSAPP ── */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}
.modal-overlay.active { display: flex; }
.modal {
    background: white;
    border-radius: 14px;
    padding: 32px;
    width: 360px;
    max-width: 95vw;
    box-shadow: 0 20px 60px rgba(0,0,0,.3);
    text-align: center;
    animation: popIn .2s ease;
}
@keyframes popIn { from { transform: scale(.92); opacity: 0 } to { transform: scale(1); opacity: 1 } }
.modal-icon  { font-size: 44px; margin-bottom: 12px; }
.modal h3    { color: #0b3d91; margin-bottom: 6px; font-size: 18px; }
.modal p     { color: #64748b; font-size: 13px; margin-bottom: 18px; }
.modal input {
    width: 100%; padding: 12px 14px;
    border: 2px solid #e2e8f0; border-radius: 8px;
    font-size: 16px; margin-bottom: 16px; outline: none;
    transition: border-color .2s;
    text-align: center; letter-spacing: 2px;
}
.modal input:focus { border-color: #25D366; }
.modal-actions { display: flex; gap: 10px; justify-content: center; }
.btn-cancel { background: #f1f5f9; color: #475569; }

/* ── HOJA A4 ── */
.page {
    width: 800px;
    max-width: 100%;
    background: white;
    box-shadow: 0 10px 40px rgba(0,0,0,.15);
    border-radius: 4px;
    overflow: hidden;
}
.stripe-top { height: 12px; background: linear-gradient(90deg,#0b3d91,#2563eb,#0b3d91); }

.content { padding: 50px; }

/* Encabezado */
.header { text-align: center; margin-bottom: 20px; }
.logo-img {
    width: 80px; height: 80px;
    object-fit: contain;
    margin-bottom: 8px;
}
.logo-fallback {
    width: 70px; height: 70px;
    background: #0b3d91;
    border-radius: 14px;
    color: white;
    font-size: 28px;
    font-weight: 900;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}
.inst-name {
    font-size: 22px;
    font-weight: 900;
    color: #0b3d91;
}
.inst-sub {
    font-size: 12px;
    color: #64748b;
    margin-top: 3px;
}

.divider {
    height: 3px;
    background: linear-gradient(90deg,#0b3d91,#3b82f6,#0b3d91);
    border-radius: 2px;
    margin: 18px 0;
}

.fecha { text-align: right; font-size: 12px; color: #64748b; margin-bottom: 28px; }

.titulo {
    text-align: center;
    font-size: 26px;
    font-weight: 900;
    color: #0b3d91;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 28px;
}

.doc {
    font-size: 14px;
    line-height: 1.9;
    text-align: justify;
    color: #334155;
}
.dest { font-weight: 700; color: #0b3d91; text-transform: uppercase; }

/* Tabla de datos */
table.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 24px;
    border-radius: 8px;
    overflow: hidden;
}
table.data-table th {
    background: #0b3d91;
    color: white;
    padding: 11px 14px;
    font-size: 12px;
    text-align: left;
    letter-spacing: .5px;
}
table.data-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}
table.data-table tr:last-child td { border-bottom: none; }
table.data-table td.lbl { color: #64748b; width: 38%; }
table.data-table td.val { font-weight: 700; color: #1e293b; }

/* Badge nivel */
.nivel-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}
.nivel-INICIAL    { background: #fce7f3; color: #9d174d; }
.nivel-PRIMARIA   { background: #dbeafe; color: #1d4ed8; }
.nivel-SECUNDARIA { background: #ede9fe; color: #5b21b6; }

.final {
    margin-top: 22px;
    font-size: 13px;
    line-height: 1.8;
    color: #475569;
}

/* Firma */
.firma-box { margin-top: 55px; display: flex; justify-content: center; }
.firma { text-align: center; width: 230px; }
.firma-space { height: 80px; }
.firma-linea { border-top: 2px solid #0b3d91; margin-bottom: 8px; }
.firma strong { font-size: 13px; color: #0b3d91; display: block; }
.firma p { font-size: 12px; color: #64748b; }

/* Footer */
.footer {
    margin-top: 30px;
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    font-size: 9px;
    color: #94a3b8;
}

/* ── IMPRESIÓN ── */
@page { size: A4; margin: 15mm; }
@media print {
    body { background: white; padding: 0; }
    .toolbar, .modal-overlay { display: none !important; }
    .page { width: 100%; box-shadow: none; border-radius: 0; }
    .stripe-top { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    table.data-table th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}

@media (max-width: 840px) {
    .page, .toolbar { width: 100%; }
    .content { padding: 30px 20px; }
    .titulo { font-size: 20px; letter-spacing: 1px; }
}
</style>
</head>
<body>

{{-- ── TOOLBAR ── --}}
<div class="toolbar">
    <div class="toolbar-title">
        <i class="fa-solid fa-file-lines" style="color:#0b3d91"></i>
        Constancia de Matrícula {{ date('Y') }}
    </div>
    <div class="toolbar-actions">
        <a href="{{ url()->previous() }}" class="btn btn-back">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('alumnos.constancia-pdf', $alumno) }}" class="btn btn-pdf" target="_blank">
            <i class="fa-solid fa-file-pdf"></i> Descargar PDF
        </a>
        <button onclick="window.print()" class="btn btn-print">
            <i class="fa-solid fa-print"></i> Imprimir
        </button>
        @if($alumno->apoderado?->telefono)
            <a href="{{ $waUrl }}" target="_blank" class="btn btn-wa">
                <i class="fa-brands fa-whatsapp"></i> WhatsApp
            </a>
        @else
            <button onclick="abrirModal()" class="btn btn-wa-dark">
                <i class="fa-brands fa-whatsapp"></i> WhatsApp
            </button>
        @endif
    </div>
</div>

{{-- ── MODAL WHATSAPP (sin teléfono) ── --}}
<div class="modal-overlay" id="modalWa">
    <div class="modal">
        <div class="modal-icon">📲</div>
        <h3>Enviar por WhatsApp</h3>
        <p>Ingresa el número del apoderado (9 dígitos)</p>
        <input type="text" id="inputTel" placeholder="987 654 321"
               maxlength="12" inputmode="numeric"
               onkeydown="if(event.key==='Enter') enviarWa()">
        <div class="modal-actions">
            <button onclick="cerrarModal()" class="btn btn-cancel">Cancelar</button>
            <button onclick="enviarWa()" class="btn btn-wa">
                <i class="fa-brands fa-whatsapp"></i> Enviar
            </button>
        </div>
    </div>
</div>

{{-- ── DOCUMENTO ── --}}
<div class="page">
    <div class="stripe-top"></div>
    <div class="content">

        {{-- Encabezado --}}
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo JEDSON" class="logo-img"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
            <div class="logo-fallback" style="display:none">J</div>
            <div class="inst-name">Colegio Pre JEDSON</div>
            <div class="inst-sub">Institución Educativa Privada &mdash; {{ $alumno->sede?->nombre ?? 'Sede Principal' }}</div>
        </div>

        <div class="divider"></div>

        <div class="fecha">Arequipa, {{ $fechaEmision }}</div>

        <div class="titulo">Constancia de Matrícula {{ date('Y') }}</div>

        {{-- Cuerpo --}}
        <div class="doc">
            Se deja constancia que el(la) estudiante
            <span class="dest">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</span>,
            identificado(a) con DNI N°
            <span class="dest">{{ $alumno->dni }}</span>,
            se encuentra debidamente matriculado(a) en el nivel
            <span class="dest">{{ $alumno->nivel_academico ?? '—' }}</span>,
            grado <span class="dest">{{ $alumno->grado_seccion ?? '—' }}</span>,
            correspondiente al periodo académico
            <span class="dest">{{ date('Y') }}</span>.
        </div>

        {{-- Tabla de datos --}}
        <table class="data-table">
            <tr><th colspan="2"><i class="fa-solid fa-graduation-cap" style="margin-right:6px"></i>Datos de Matrícula</th></tr>
            <tr>
                <td class="lbl">Alumno / Alumna</td>
                <td class="val">{{ strtoupper($alumno->apellidos . ', ' . $alumno->nombres) }}</td>
            </tr>
            <tr>
                <td class="lbl">DNI</td>
                <td class="val">{{ $alumno->dni }}</td>
            </tr>
            <tr>
                <td class="lbl">Fecha de Nacimiento</td>
                <td class="val">{{ $alumno->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</td>
            </tr>
            <tr>
                <td class="lbl">Nivel</td>
                <td class="val">
                    <span class="nivel-badge nivel-{{ $alumno->nivel_academico ?? 'PRIMARIA' }}">
                        {{ $alumno->nivel_academico ?? '—' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="lbl">Grado / Sección</td>
                <td class="val">{{ $alumno->grado_seccion ?? '—' }}</td>
            </tr>
            <tr>
                <td class="lbl">Sede</td>
                <td class="val">{{ $alumno->sede?->nombre ?? 'Sede Principal' }}</td>
            </tr>
            @if($alumno->apoderado)
            <tr>
                <td class="lbl">Apoderado / Apoderada</td>
                <td class="val">
                    {{ strtoupper($alumno->apoderado->nombre_completo) }}
                    @if($alumno->apoderado->parentesco)
                        <span style="font-weight:400;color:#64748b;font-size:12px">({{ $alumno->apoderado->parentesco }})</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="lbl">Teléfono del Apoderado</td>
                <td class="val">{{ $alumno->apoderado->telefono ?? '—' }}</td>
            </tr>
            @endif
            <tr>
                <td class="lbl">Fecha de emisión</td>
                <td class="val">{{ $fechaEmision }}</td>
            </tr>
        </table>

        <p class="final">
            Se expide la presente constancia a solicitud del interesado para los fines que estime conveniente.
        </p>

        {{-- Firma --}}
        @php $firmaUrl = \App\Helpers\ConfiguracionColegio::firmaUrl(); @endphp
        <div class="firma-box">
            <div class="firma">
                @if($firmaUrl)
                    <div class="firma-imgs">
                        <img src="{{ $firmaUrl }}" class="firma-img-firma" alt="Firma Director">
                    </div>
                @else
                    <div class="firma-space"></div>
                @endif
                <div class="firma-linea"></div>
                <strong>Director(a)</strong>
                <p>Colegio Pre JEDSON</p>
                <p>Arequipa</p>
            </div>
        </div>

        {{-- Footer interno --}}
        <div class="footer">
            <span>Colegio Pre JEDSON &bull; Institución Educativa Privada &bull; Arequipa, Perú</span>
            <span>Constancia N° {{ str_pad($alumno->id, 6, '0', STR_PAD_LEFT) }}-{{ date('Y') }}</span>
        </div>

    </div>
</div>

<script>
const msgWa = `✅ *CONSTANCIA DE MATRÍCULA*\n━━━━━━━━━━━━━━━━━\n📋 *Alumno:* {{ strtoupper($alumno->apellidos . ', ' . $alumno->nombres) }}\n🪪 *DNI:* {{ $alumno->dni }}\n📚 *Nivel:* {{ $alumno->nivel_academico ?? '—' }}\n🎓 *Grado:* {{ $alumno->grado_seccion ?? '—' }}\n📅 *Periodo:* {{ date('Y') }}\n👤 *Apoderado:* {{ strtoupper($alumno->apoderado?->nombre_completo ?? '—') }}\n📆 *Fecha:* {{ $fechaEmision }}\n━━━━━━━━━━━━━━━━━\nColegio Pre JEDSON - Arequipa`;

function buildWaUrl(num) {
    let tel = num.replace(/\D/g,'');
    if (tel.length === 9) tel = '51' + tel;
    return 'https://wa.me/' + tel + '?text=' + encodeURIComponent(msgWa);
}
function abrirModal() {
    document.getElementById('modalWa').classList.add('active');
    setTimeout(() => document.getElementById('inputTel').focus(), 80);
}
function cerrarModal() {
    document.getElementById('modalWa').classList.remove('active');
    document.getElementById('inputTel').value = '';
}
function enviarWa() {
    const num = document.getElementById('inputTel').value.trim();
    if (!num) { document.getElementById('inputTel').style.borderColor='#ef4444'; return; }
    window.open(buildWaUrl(num), '_blank');
    cerrarModal();
}
document.getElementById('modalWa').addEventListener('click', e => { if (e.target===e.currentTarget) cerrarModal(); });
</script>
</body>
</html>
