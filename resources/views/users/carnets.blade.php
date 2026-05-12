<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carnets Personal — Colegio Pre JEDSON</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:Arial,Helvetica,sans-serif;background:#e8e8e8;padding:14px;}

/* ── Barra de control ── */
.ctrl-bar{
    background:linear-gradient(135deg,#1e3a5f,#2563eb);
    color:#fff;padding:13px 22px;border-radius:12px;margin-bottom:18px;
    display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;
    box-shadow:0 4px 16px rgba(37,99,235,.3);
}
.ctrl-bar h2{font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px;}
.ctrl-bar small{font-size:11px;opacity:.75;display:block;margin-top:3px;}
.btn-print{
    background:#fff;color:#1e3a5f;border:none;padding:10px 24px;
    border-radius:8px;font-weight:700;font-size:14px;cursor:pointer;
    transition:all .15s;display:flex;align-items:center;gap:8px;
}
.btn-print:hover{background:#eff6ff;transform:translateY(-1px);}
.btn-close{
    background:rgba(255,255,255,.18);color:#fff;
    border:1px solid rgba(255,255,255,.4);padding:10px 18px;
    border-radius:8px;font-size:13px;cursor:pointer;
    display:flex;align-items:center;gap:6px;transition:all .15s;
}
.btn-close:hover{background:rgba(255,255,255,.28);}

/* ── Hoja A4 ── */
.hoja{
    background:#fff;
    width:210mm;
    min-height:297mm;
    margin:0 auto 20px;
    padding:10mm;
    box-shadow:0 4px 24px rgba(0,0,0,.18);
    border-radius:6px;
    display:flex;
    flex-direction:column;
    gap:8mm;
}

/* ── Par frente + dorso ── */
.par{
    display:flex;
    gap:8mm;
    align-items:flex-start;
    justify-content:center;
    break-inside:avoid;
    page-break-inside:avoid;
}
.col-carnet{
    display:flex;flex-direction:column;align-items:center;gap:2mm;
}
.lado-label{font-size:7pt;color:#64748b;font-style:italic;text-align:center;}
.sep{border:none;border-top:1.5px dashed #cbd5e1;width:100%;margin:0;}

/* ── CARNET BASE ── */
.carnet{
    width:60mm;
    height:95mm;
    border-radius:4mm;
    padding:3.5mm 3mm 3mm;
    display:flex;
    flex-direction:column;
    align-items:center;
    font-family:Arial,Helvetica,sans-serif;
    position:relative;
    overflow:hidden;
    -webkit-print-color-adjust:exact;
    print-color-adjust:exact;
    border:2px solid rgba(255,255,255,.4);
    color:#fff;
    flex-shrink:0;
}
/* Variantes de color por rol */
.carnet.rol-administrador{background:linear-gradient(150deg,#0f172a 0%,#1e3a5f 40%,#2563eb 75%,#3b82f6 100%);}
.carnet.rol-docente      {background:linear-gradient(150deg,#1e1035 0%,#2d1b69 40%,#4c1d95 75%,#7c3aed 100%);}
.carnet.rol-auxiliar     {background:linear-gradient(150deg,#042f2e 0%,#064e3b 40%,#047857 75%,#10b981 100%);}
.carnet.rol-estudiante   {background:linear-gradient(150deg,#0f172a 0%,#1e3a5f 40%,#2563eb 75%,#3b82f6 100%);}

/* Borde por rol */
.carnet.rol-administrador{border-color:#60a5fa;}
.carnet.rol-docente      {border-color:#a78bfa;}
.carnet.rol-auxiliar     {border-color:#34d399;}
.carnet.rol-estudiante   {border-color:#60a5fa;}

.carnet-bg{position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;}

/* ── FRENTE ── */
.frente .fila-top{
    display:flex;gap:2mm;width:100%;align-items:flex-start;
    position:relative;z-index:1;margin-bottom:2mm;flex-shrink:0;
}
/* Avatar inicial (sin foto para usuarios) */
.frente .avatar-box{
    width:18mm;height:22mm;
    background:rgba(255,255,255,.15);
    border:1.5px solid rgba(255,255,255,.4);
    border-radius:2mm;display:flex;align-items:center;
    justify-content:center;overflow:hidden;flex-shrink:0;
}
.frente .avatar-inicial{
    font-size:20px;font-weight:900;color:rgba(255,255,255,.85);
    text-transform:uppercase;
}
.frente .inst-box{
    flex:1;display:flex;flex-direction:column;
    align-items:center;justify-content:center;gap:0.5mm;
}
.frente .inst-sup{color:#93c5fd;font-size:5pt;font-weight:900;letter-spacing:1px;text-transform:uppercase;}
.frente .inst-nom{color:#fff;font-size:8pt;font-weight:900;font-style:italic;text-align:center;line-height:1.15;}
.inst-logo-img{width:16mm;height:auto;margin-top:1mm;filter:drop-shadow(0 1px 4px rgba(0,0,0,.4));}
.inst-logo-svg{
    width:16mm;height:16mm;margin-top:1mm;flex-shrink:0;
}

/* Nombre */
.nombre-box{
    background:rgba(255,255,255,.92);width:100%;
    text-align:center;padding:1mm 1.5mm;
    border-radius:2mm;position:relative;z-index:1;
    margin-bottom:1mm;overflow:hidden;max-height:9mm;
}
.nombre-txt{
    font-weight:900;color:#1e3a5f;
    text-transform:uppercase;line-height:1.2;
    word-break:break-word;overflow:hidden;
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
}

/* DNI */
.dni-pill{
    background:rgba(255,255,255,.18);border-radius:8mm;
    padding:.8mm 3mm;position:relative;z-index:1;
    margin-bottom:1mm;border:1px solid rgba(255,255,255,.25);
}
.dni-txt{color:#fff;font-size:7pt;font-weight:700;}

/* QR */
.qr-img{
    width:21mm;height:21mm;background:#fff;padding:1mm;
    border-radius:2mm;box-shadow:0 2px 8px rgba(0,0,0,.3);
    position:relative;z-index:1;display:block;
    margin-bottom:1mm;object-fit:contain;
}
.qr-vacio{
    width:21mm;height:21mm;background:rgba(255,255,255,.12);
    border:1.5px dashed rgba(255,255,255,.4);border-radius:2mm;
    display:flex;align-items:center;justify-content:center;
    font-size:6.5pt;color:rgba(255,255,255,.5);
    position:relative;z-index:1;margin-bottom:1mm;text-align:center;line-height:1.4;
}

/* Rol pill */
.rol-pill{
    border-radius:8mm;padding:1mm 2.5mm;
    position:relative;z-index:1;width:96%;text-align:center;
}
.rol-pill.rol-administrador{background:linear-gradient(90deg,#1d4ed8,#2563eb);}
.rol-pill.rol-docente      {background:linear-gradient(90deg,#4c1d95,#7c3aed);}
.rol-pill.rol-auxiliar     {background:linear-gradient(90deg,#047857,#059669);}
.rol-pill.rol-estudiante   {background:linear-gradient(90deg,#1d4ed8,#2563eb);}
.rol-txt{
    color:#fff;font-size:6pt;font-weight:900;
    text-transform:uppercase;letter-spacing:.4px;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}

/* ── DORSO ── */
.dorso{justify-content:space-between;}
.dorso-top{
    display:flex;flex-direction:column;align-items:center;
    position:relative;z-index:1;width:100%;gap:1mm;margin-top:2mm;
}
.avatar-grande{
    width:22mm;height:22mm;border-radius:50%;
    border:2px solid rgba(255,255,255,.7);
    box-shadow:0 2px 8px rgba(0,0,0,.4);
    display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.15);flex-shrink:0;
    font-size:24px;font-weight:900;color:rgba(255,255,255,.9);
    text-transform:uppercase;
}
.dorso-bot{
    position:relative;z-index:1;width:100%;
    display:flex;flex-direction:column;gap:1.2mm;margin-bottom:2mm;
}
.inst-footer{
    background:rgba(255,255,255,.08);border-radius:1.5mm;
    padding:1.2mm 2.5mm;text-align:center;
    border:1px solid rgba(255,255,255,.1);
}
.inst-footer .lbl{color:rgba(255,255,255,.45);font-size:4.5pt;letter-spacing:.5px;text-transform:uppercase;}
.inst-footer .val{color:#fff;font-size:6pt;font-weight:700;}

.nivel-pill{
    background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
    border-radius:1.5mm;padding:1mm 2.5mm;text-align:center;position:relative;z-index:1;
}
.nivel-lbl{color:rgba(255,255,255,.45);font-size:4.5pt;text-transform:uppercase;letter-spacing:.5px;}
.nivel-val{color:#93c5fd;font-size:6pt;font-weight:700;}

/* ── IMPRESIÓN ── */
@media print {
    .ctrl-bar{display:none!important;}
    body{background:#fff;padding:0;}
    .hoja{
        box-shadow:none;border-radius:0;margin:0;
        page-break-after:always;break-after:page;
    }
    .hoja:last-child{page-break-after:auto;break-after:auto;}
    .carnet{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
}
@page{size:A4 portrait;margin:8mm;}
</style>
</head>
<body>

<!-- Barra de control -->
<div class="ctrl-bar">
    <div>
        <h2><i class="fa-solid fa-id-card"></i> Carnets — Personal Educativo</h2>
        <small>{{ count($users) }} usuario{{ count($users) !== 1 ? 's' : '' }} &nbsp;|&nbsp; 2 por hoja &nbsp;|&nbsp; Frente + Dorso</small>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn-print" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Imprimir / Guardar PDF
        </button>
        <button class="btn-close" onclick="window.close()">
            <i class="fa-solid fa-xmark"></i> Cerrar
        </button>
    </div>
</div>

@php
$grupos = array_chunk($users->all(), 2);
$logoFile = asset('images/logo.png');
@endphp

@foreach($grupos as $grupo)
<div class="hoja">
@php $primero = true; @endphp
@foreach($grupo as $user)
    @php
        $nombre   = strtoupper($user->name);
        $dni      = $user->dni ?? '—';
        $rol      = $user->user_type;
        $rolLabel = ucfirst($rol);
        $sede     = $user->sede?->nombre ?? null;
        $inicial  = strtoupper(substr($user->name, 0, 1));

        $nLen       = mb_strlen($nombre);
        $nombreFt   = $nLen > 40 ? '5pt' : ($nLen > 30 ? '5.5pt' : ($nLen > 22 ? '6pt' : '7pt'));
        $nombreStyle= "font-size:{$nombreFt}";

        $qrPath = $user->qr_code_path
            ? Storage::disk('public')->path($user->qr_code_path)
            : null;
        $qrUrl  = ($qrPath && file_exists($qrPath))
            ? Storage::url($user->qr_code_path)
            : null;

        $rolIcons = [
            'administrador' => 'fa-shield-halved',
            'docente'       => 'fa-chalkboard-user',
            'auxiliar'      => 'fa-clipboard-list',
            'estudiante'    => 'fa-graduation-cap',
        ];
        $rolIcon = $rolIcons[$rol] ?? 'fa-user';
    @endphp

    @if(!$primero)<hr class="sep">@endif
    @php $primero = false; @endphp

    <div class="par">

        <!-- ══ FRENTE ══ -->
        <div class="col-carnet">
            <div class="lado-label">— Frente —</div>
            <div class="carnet frente rol-{{ $rol }}">
                <svg class="carnet-bg" viewBox="0 0 170 269" fill="none">
                    <ellipse cx="150" cy="42" rx="65" ry="65" fill="rgba(255,255,255,0.06)"/>
                    <ellipse cx="14"  cy="224" rx="72" ry="72" fill="rgba(255,255,255,0.05)"/>
                    <circle  cx="136" cy="237" r="44"  fill="rgba(255,255,255,0.04)"/>
                    <path d="M0 180 Q45 143 85 180 Q128 217 170 180 L170 269 L0 269 Z" fill="rgba(255,255,255,0.03)"/>
                </svg>

                <!-- Fila superior: avatar inicial + institución -->
                <div class="fila-top">
                    <div class="avatar-box">
                        <span class="avatar-inicial">{{ $inicial }}</span>
                    </div>
                    <div class="inst-box">
                        <div class="inst-sup">Personal</div>
                        <div class="inst-nom">Colegio Pre<br>JEDSON</div>
                        <img src="{{ $logoFile }}" class="inst-logo-img" alt="logo"
                             onerror="this.style.display='none';this.nextSibling.style.display='inline'">
                        <svg style="display:none;" class="inst-logo-svg" viewBox="0 0 50 50" fill="none">
                            <circle cx="25" cy="25" r="23" fill="rgba(255,255,255,0.2)" stroke="rgba(255,255,255,0.55)" stroke-width="1.5"/>
                            <text x="25" y="33" text-anchor="middle" font-family="Arial" font-weight="900" font-size="24" fill="white">J</text>
                        </svg>
                    </div>
                </div>

                <!-- Nombre -->
                <div class="nombre-box">
                    <div class="nombre-txt" style="{{ $nombreStyle }}">{{ $nombre }}</div>
                </div>

                <!-- DNI -->
                <div class="dni-pill">
                    <span class="dni-txt">DNI: {{ $dni }}</span>
                </div>

                <!-- QR -->
                @if($qrUrl)
                    <img src="{{ $qrUrl }}" class="qr-img" alt="QR">
                @else
                    <div class="qr-vacio">QR<br>sin generar</div>
                @endif

                <!-- Rol -->
                <div class="rol-pill rol-{{ $rol }}">
                    <span class="rol-txt"><i class="fa-solid {{ $rolIcon }}"></i> {{ $rolLabel }}</span>
                </div>
            </div>
        </div>

        <!-- ══ DORSO ══ -->
        <div class="col-carnet">
            <div class="lado-label">— Dorso —</div>
            <div class="carnet dorso rol-{{ $rol }}">
                <svg class="carnet-bg" viewBox="0 0 170 269" fill="none">
                    <ellipse cx="150" cy="42" rx="65" ry="65" fill="rgba(255,255,255,0.06)"/>
                    <ellipse cx="14"  cy="224" rx="72" ry="72" fill="rgba(255,255,255,0.05)"/>
                </svg>

                <div class="dorso-top">
                    <div class="avatar-grande">{{ $inicial }}</div>
                    <div class="nombre-box" style="margin-bottom:0;">
                        <div class="nombre-txt" style="{{ $nombreStyle }}">{{ $nombre }}</div>
                    </div>
                    <div class="dni-pill" style="margin-bottom:0;">
                        <span class="dni-txt">DNI: {{ $dni }}</span>
                    </div>
                </div>

                <div class="dorso-bot">
                    <div class="nivel-pill">
                        <div class="nivel-lbl">Cargo / Perfil</div>
                        <div class="nivel-val">{{ $rolLabel }}</div>
                    </div>
                    <div class="rol-pill rol-{{ $rol }}">
                        <span class="rol-txt">Año Académico {{ date('Y') }}</span>
                    </div>
                    <div class="inst-footer">
                        <div class="lbl">Institución Educativa</div>
                        <div class="val">Colegio Pre JEDSON</div>
                    </div>
                    @if($sede)
                    <div class="inst-footer">
                        <div class="lbl">Sede</div>
                        <div class="val">{{ $sede }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>{{-- .par --}}
@endforeach
</div>{{-- .hoja --}}
@endforeach

<script>
window.addEventListener('load', function() {
    setTimeout(function(){ window.print(); }, 1200);
});
</script>
</body>
</html>
