<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    font-size: 9.5px;
    color: #000;
    background: #fff;
}

/* ── Página tamaño ticket (media carta ancho) ── */
@page {
    size: 148mm 210mm;   /* A5 portrait */
    margin: 8mm 10mm;
}

.page { width: 100%; background: #fff; }

/* ── Borde superior grueso ── */
.borde-top    { border-top: 3px solid #000; margin-bottom: 6px; }
.borde-medium { border-top: 1.5px solid #000; margin: 5px 0; }
.borde-dash   { border-top: 1px dashed #000; margin: 5px 0; }
.borde-bottom { border-top: 3px double #000; margin-top: 6px; }

/* ── Encabezado ── */
.header { text-align: center; padding: 4px 0; }
.logo-img {
    width: 44px;
    height: 44px;
    object-fit: contain;
    display: block;
    margin: 0 auto 4px;
}
.logo-fallback {
    width: 44px; height: 44px;
    border: 2px solid #000;
    border-radius: 4px;
    display: inline-block;
    line-height: 40px;
    font-weight: 900;
    font-size: 18px;
    text-align: center;
    margin-bottom: 4px;
}
.inst-nombre {
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.inst-sub {
    font-size: 7.5px;
    color: #333;
    margin-top: 1px;
}

/* ── Título del comprobante ── */
.comp-titulo {
    text-align: center;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    padding: 5px 0 3px;
}
.comp-num {
    text-align: center;
    font-size: 8px;
    color: #333;
    margin-bottom: 2px;
}

/* ── Datos en dos columnas ── */
.fila {
    display: table;
    width: 100%;
    padding: 2.5px 0;
}
.fila-lbl {
    display: table-cell;
    color: #444;
    font-size: 8.5px;
    width: 42%;
    vertical-align: top;
}
.fila-val {
    display: table-cell;
    font-weight: bold;
    font-size: 8.5px;
    vertical-align: top;
    text-align: right;
}
.fila-val-left {
    display: table-cell;
    font-weight: bold;
    font-size: 8.5px;
    vertical-align: top;
}

/* ── Sección de título ── */
.sec-titulo {
    font-size: 8px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 3px 0 1px;
    color: #000;
}

/* ── Alumno destacado ── */
.alumno-box {
    border: 1px solid #000;
    padding: 5px 7px;
    margin: 4px 0;
}
.alumno-nombre {
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}
.alumno-sub {
    font-size: 8px;
    color: #333;
    margin-top: 1px;
}

/* ── Detalle de pagos (estilo boleta) ── */
.detalle-header {
    display: table;
    width: 100%;
    border-bottom: 1px solid #000;
    padding-bottom: 2px;
    margin-bottom: 2px;
}
.dh-desc { display: table-cell; font-size: 7.5px; font-weight: 900; text-transform: uppercase; width: 60%; }
.dh-monto{ display: table-cell; font-size: 7.5px; font-weight: 900; text-transform: uppercase; text-align: right; }

.detalle-fila {
    display: table;
    width: 100%;
    padding: 2px 0;
    border-bottom: 1px dotted #bbb;
}
.df-desc  { display: table-cell; font-size: 8.5px; width: 60%; vertical-align: middle; }
.df-monto { display: table-cell; font-size: 8.5px; font-weight: bold; text-align: right; vertical-align: middle; }

.total-box {
    display: table;
    width: 100%;
    border-top: 2px solid #000;
    padding-top: 4px;
    margin-top: 3px;
}
.total-lbl { display: table-cell; font-size: 10px; font-weight: 900; text-transform: uppercase; }
.total-val { display: table-cell; font-size: 12px; font-weight: 900; text-align: right; }

/* ── Estado de pago ── */
.estado-box {
    text-align: center;
    border: 2px solid #000;
    padding: 3px 8px;
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 5px 0;
    display: inline-block;
    width: 100%;
}
.estado-pagado   { border-style: double; }
.estado-pendiente{ border-style: dashed; }

/* ── QR ── */
.qr-box { text-align: center; margin: 6px 0; }
.qr-box img { width: 55px; height: 55px; }
.qr-label { font-size: 7px; color: #555; margin-top: 2px; }

/* ── Firma ── */
.firma-box {
    margin-top: 10px;
    text-align: center;
}
.firma-linea {
    border-top: 1px solid #000;
    width: 110px;
    margin: 0 auto 3px;
}
.firma-txt { font-size: 8px; }

/* ── Footer ── */
.footer {
    margin-top: 6px;
    text-align: center;
    font-size: 7px;
    color: #555;
}

/* ── Marca de agua PAGADO (solo cuando está pagado) ── */
.sello-pagado {
    text-align: center;
    font-size: 22px;
    font-weight: 900;
    letter-spacing: 4px;
    color: #bbb;
    border: 3px solid #bbb;
    padding: 2px 10px;
    display: inline-block;
    transform: rotate(-15deg);
    margin: 6px auto;
    width: auto;
}
</style>
</head>
<body>
<div class="page">

@php
    $mat = $alumno->matriculas->sortByDesc('periodo')->first();
    $pago = $mat?->pagoMatricula;
    $nroTicket = 'TKT-' . date('Y') . '-' . str_pad($alumno->id, 5, '0', STR_PAD_LEFT);
    $totalAnual = $pago ? ($pago->pension_mensual * ($pago->numero_pensiones ?? 10)) : 0;
    $descuento  = ($alumno->tipo_descuento !== 'ninguno') ? $alumno->monto_descuento : 0;
    $totalPagar = $pago ? ($pago->monto_matricula + $totalAnual - $descuento) : 0;
    $estadoPago = strtolower($pago?->estado_pago ?? 'pendiente');
@endphp

<div class="borde-top"></div>

{{-- ENCABEZADO --}}
<div class="header">
    <img src="{{ public_path('images/logo.png') }}" class="logo-img" alt="Logo"
         onerror="this.style.display='none'">
    <div class="inst-nombre">Colegio Pre JEDSON</div>
    <div class="inst-sub">Institución Educativa Privada — Arequipa, Perú</div>
    <div class="inst-sub">{{ $alumno->sede?->nombre ?? 'Sede Principal' }}</div>
</div>

<div class="borde-medium"></div>

{{-- TÍTULO COMPROBANTE --}}
<div class="comp-titulo">Comprobante de Matrícula</div>
<div class="comp-num">
    N° {{ $nroTicket }} &nbsp;|&nbsp;
    Período: {{ $mat?->periodo ?? date('Y') }} &nbsp;|&nbsp;
    Emitido: {{ $fechaEmision }}
</div>

<div class="borde-dash"></div>

{{-- DATOS DEL ALUMNO --}}
<div class="alumno-box">
    <div class="alumno-nombre">{{ $alumno->apellidos }}, {{ $alumno->nombres }}</div>
    <div class="alumno-sub">
        DNI: <strong>{{ $alumno->dni }}</strong>
        &nbsp;&nbsp;
        {{ $alumno->nivel_academico ?? '' }}
        @if($alumno->grado_seccion) — {{ $alumno->grado_seccion }} @endif
    </div>
    @if($alumno->apoderado)
    <div class="alumno-sub" style="margin-top:2px;">
        Apoderado: <strong>{{ $alumno->apoderado->nombre_completo }}</strong>
        @if($alumno->apoderado->telefono)
            &nbsp;| Tel: {{ $alumno->apoderado->telefono }}
        @endif
    </div>
    @endif
</div>

{{-- DATOS DE MATRÍCULA --}}
@if($mat)
<div class="sec-titulo">Datos de Matrícula</div>
<div class="fila">
    <span class="fila-lbl">Código:</span>
    <span class="fila-val">{{ $mat->codigo_matricula }}</span>
</div>
<div class="fila">
    <span class="fila-lbl">Nivel / Grado:</span>
    <span class="fila-val">{{ $mat->nivel_academico }} — {{ $mat->grado_seccion }}</span>
</div>
<div class="fila">
    <span class="fila-lbl">Situación:</span>
    <span class="fila-val">{{ $mat->situacion }}</span>
</div>
<div class="fila">
    <span class="fila-lbl">Modalidad de pago:</span>
    <span class="fila-val">{{ ucfirst($mat->modalidad_pago) }}</span>
</div>
@endif

{{-- DETALLE DE PAGO --}}
@if($pago)
<div class="borde-dash"></div>
<div class="sec-titulo">Detalle del Pago</div>

<div class="detalle-header">
    <span class="dh-desc">Concepto</span>
    <span class="dh-monto">Monto</span>
</div>

<div class="detalle-fila">
    <span class="df-desc">Matrícula {{ $mat->periodo }}</span>
    <span class="df-monto">S/ {{ number_format($pago->monto_matricula, 2) }}</span>
</div>
<div class="detalle-fila">
    <span class="df-desc">Pensión mensual x {{ $pago->numero_pensiones ?? 10 }}</span>
    <span class="df-monto">S/ {{ number_format($pago->pension_mensual, 2) }} c/u</span>
</div>
<div class="detalle-fila" style="border-bottom:none;">
    <span class="df-desc">Subtotal pensiones</span>
    <span class="df-monto">S/ {{ number_format($totalAnual, 2) }}</span>
</div>

@if($descuento > 0)
<div class="borde-dash"></div>
<div class="detalle-fila" style="border-bottom:none;">
    <span class="df-desc">Descuento ({{ ucfirst($alumno->tipo_descuento) }})</span>
    <span class="df-monto">- S/ {{ number_format($descuento, 2) }}</span>
</div>
@endif

<div class="total-box">
    <span class="total-lbl">Total a pagar</span>
    <span class="total-val">S/ {{ number_format($totalPagar, 2) }}</span>
</div>

<div class="borde-dash"></div>

{{-- ESTADO DESTACADO --}}
<div class="estado-box estado-{{ $estadoPago }}">
    Estado: {{ strtoupper($pago->estado_pago) }}
</div>
@endif

{{-- QR --}}
@if($alumno->qr_code_path && file_exists(storage_path('app/public/' . $alumno->qr_code_path)))
<div class="borde-dash"></div>
<div class="qr-box">
    <img src="{{ storage_path('app/public/' . $alumno->qr_code_path) }}" alt="QR">
    <div class="qr-label">{{ $alumno->dni }} — Colegio Pre JEDSON</div>
</div>
@endif

{{-- FIRMA --}}
<div class="borde-dash"></div>
<div class="firma-box">
    <div style="height: 30px;"></div>
    <div class="firma-linea"></div>
    <div class="firma-txt"><strong>Director(a) / Administración</strong></div>
    <div class="firma-txt">Colegio Pre JEDSON — Arequipa</div>
</div>

<div class="borde-bottom"></div>

{{-- FOOTER --}}
<div class="footer">
    <p>Este comprobante acredita la matrícula del estudiante para el período académico indicado.</p>
    <p>{{ $nroTicket }} &bull; {{ $fechaEmision }} &bull; Colegio Pre JEDSON</p>
</div>

</div>
</body>
</html>
